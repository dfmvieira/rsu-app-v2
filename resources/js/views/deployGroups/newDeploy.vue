<template>
  <CCard style="width: 70%;">
    <CCardHeader>
      <CIcon name="cil-notes"/> Insert a new deploy group
    </CCardHeader>
    <CCardBody>
      <CForm>
        <CInput
			label="Name"
			horizontal
			:lazy="false"
			:value.sync="deploy.name"
			
			placeholder="Name"
			invalidFeedback="This is a required field and must be at least 1 character"
        />
        <CTextarea
			label="Notes"
			horizontal
			:lazy="false"
			:value.sync="deploy.notes"
			vertical
            rows="4"
			placeholder="Notes"
			invalidFeedback="This is a required field and must be at least 1 character"
        />

		<CRow>
			<CCol lg="6">
				<Multiselect 
					:multiple="true"
					:close-on-select="false"
					v-model="signs" 
					:options="signsOptions"
					placeholder="Please select signs" 
					label="label" 
					track-by="id"
					@input="updateSigns"
					class="myMultiSelect"
				/>
			</CCol>
			<CCol lg="6">
				<Multiselect 
					:multiple="true"
					:close-on-select="false"
					v-model="users" 
					:options="usersOptions"
					placeholder="Please select users"
					label="label" 
					track-by="id"  
					@input="updateUsers"    
					class="myMultiSelect"
				/>
			</CCol>
		</CRow>

        <CRow style="margin-top: 20px; text-align: right">
          	<CButton color="primary" @click="submit">Submit</CButton>
          
			<CButton 
				class="ml-1"
				color="danger"
				@click="reset"
			>
            	Reset
          	</CButton>
        </CRow>
      </CForm>
    </CCardBody>

	<CToaster :autohide="3000">
            <template v-for="toast in fixedToasts">
                <CToast
                :key="'toast' + toast"
                :show="true"
                header="Info"
                style="max-height: 90px;"
                >
                    {{ toastMessage }}
                </CToast>
            </template>
        </CToaster>
  </CCard>
</template>

<script>
import axios from 'axios'
import { validationMixin } from "vuelidate"
import { required, minLength} from "vuelidate/lib/validators"
import Multiselect from 'vue-multiselect'
import 'vue-multiselect/dist/vue-multiselect.min.css'


export default {
  name: 'InsertViennaSign',

  components: {
    Multiselect,
  },
   
  data() {
    return {
      form: this.getEmptyForm(),
      horizontal: { label:'col-3', input:'col-9' },
      submitted: false,
      signsOptions: [],
      usersOptions: [],
      deploy: {
        name: '',
        notes: '',
        signs: [],
        users: [],
      },
      signs: [],
      users: [],
      showMessage: false,
      message: '',
      dismissSecs: 7,
      dismissCountDown: 0,
      showDismissibleAlert: false,

	  // TOAST
		toastMessage: '',
		fixedToasts: 0
    }
  },
  computed: {
    formString () { return JSON.stringify(this.form, null, 4) },
    isValid () { return !this.$v.form.$invalid },
    isDirty () { return this.$v.form.$anyDirty },
  },
  mixins: [validationMixin],
  validations: {
    form: {
      name: {
        required,
        minLength: minLength(2)
      },
    }
  },
  methods: {

    getIVIS() {
		axios.get('api/deploy/signsfordeploy?token=' + localStorage.getItem("api_token"))
		.then(response => {
			Object.keys(response.data).forEach(key => {
				let i = {
					id: response.data[key].id,
					label: response.data[key].name + ' - ' + response.data[key].GUID
				}
				this.signsOptions.push(i)
			})
		}).catch(err => {
			console.log(err)
		})
	},

    getUsers() {
		axios.get('api/user/techniciansofentity?token=' + localStorage.getItem("api_token"))
		.then(response => {
			response.data.forEach(item => {
				let i = {
					id: item.id,
					label: item.name + ' - ' + item.email
				}
				this.usersOptions.push(i)
			})
		}).catch(err => {
			console.log(err)
		})
    },

    updateSigns(signs) {
      let ids = [];

      signs.forEach((sign) => {
            ids.push(sign.id);
      });

      this.deploy.signs = ids;
    },

    updateUsers(users) {
      let ids = [];

      users.forEach((user) => {
            ids.push(user.id);
      });

      this.deploy.users = ids;
    },
      
    checkIfValid (fieldName) {
      const field = this.$v.form[fieldName]
      if (!field.$dirty) {
        return null
      } 
      return !(field.$invalid || field.$model === '')
    },


    reset () {
      this.deploy = this.getEmptyForm()
	  this.signs = []
	  this.users = []
      this.submitted = false
      this.$v.$reset()
    },
    
    getEmptyForm () {
		return {
			name: '',
			notes: '',
			signs: [],
			users: []
		}
    },

    async load() {
        this.deploy = await this.getEmptyForm();
        await this.$nextTick() // waits for the next event tick before completeing function.
    },


    submit() {
      axios.post('api/deploy/insert?token=' + localStorage.getItem("api_token"), this.deploy)
      .then(response => {
		  this.insertToast(response.data.message)
          this.deploy = this.getEmptyForm();
		  this.signs = []
		  this.users = []
          this.submitted = true;
      })
      .catch(error => {
          console.log(error)
          if (error.response.data.errors.id) {
			this.successMessage = error.response.data.errors.id[0];
			this.showError = true;
          } else if (error.response.data.errors.name) {
              this.successMessage = error.response.data.errors.name[0];
              this.showError = true;
          } else if (error.response.data.errors.image) {
              this.successMessage = error.response.data.errors.image[0];
              this.showError = true;
          }
      })
    },


    countDownChanged (dismissCountDown) {
        this.dismissCountDown = dismissCountDown
    },

    showAlert () {
        this.dismissCountDown = this.dismissSecs
    },

	// Insert Toasts
	insertToast(message) {
		this.fixedToasts++
		this.toastMessage = message
	},
  },

  mounted() {
    this.getIVIS();
    this.getUsers();
  },
}
</script>


<style>

	.multiselect.myMultiSelect .multiselect__tags,
	.multiselect.myMultiSelect .multiselect__tags span,
	.multiselect.myMultiSelect .multiselect__tags input {
		background: #393a42
	}

	.multiselect.myMultiSelect .multiselect__content {
		background: #393a42;
		color: white;
	}

	.multiselect.myMultiSelect .multiselect__tag,
	.multiselect.myMultiSelect .multiselect__tag span {
		background: #23232e !important;
	}

	.multiselect.myMultiSelect .multiselect__option--highlight::after { 
		background: #23232e;
	}

	.multiselect.myMultiSelect .multiselect__option--highlight  {
		background: #23232e;
	}

</style>
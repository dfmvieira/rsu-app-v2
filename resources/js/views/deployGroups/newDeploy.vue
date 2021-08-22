<template>
  <CCard style="width: 70%;">
    <CCardHeader>
      <CIcon name="cil-notes"/> Insert Vienna Sign
    </CCardHeader>
    <CCardBody>
      <CAlert
              :show.sync="dismissCountDown"
              color="primary"
              fade
            >
              ({{dismissCountDown}}) {{ message }}
            </CAlert>
      
      <CForm>

        <CInput
          label="Name"
          horizontal
          :lazy="false"
          :value.sync="deploy.name"
          
          placeholder="Name"
          invalidFeedback="This is a required field and must be at least 1 character"
        />
        <CInput
          label="Notes"
          horizontal
          :lazy="false"
          :value.sync="deploy.notes"
          
          placeholder="Notes"
          invalidFeedback="This is a required field and must be at least 1 character"
        />
        
        <Multiselect 
          :multiple="true"
          :close-on-select="false"
          v-model="signs" 
          :options="options"
          placeholder="Please select sigs" 
          label="label" 
          track-by="value"
          @input="updateSigns"
          />

          <Multiselect 
            :multiple="true"
            :close-on-select="false"
            v-model="users" 
            :options="options2"
            placeholder="Please select users"
            label="label" 
            track-by="id"  
            @input="updateUsers"      
            
          />



        
        
        <CRow>
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
      options: [],
      options2: [],
      deploy: {

        name: '',
        notes: '',
        status: 0,
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
      id: {
        required,
        minLength: minLength(2)
      },
      name: {
        required,
        minLength: minLength(2)
      },
      IDCategory: {
        /* required */
      },
      image: {
        /* required */
      }
    }
  },
  methods: {

    getIVIS() {
      axios.get('api/ivisign/unpublished'+ '?token=' + localStorage.getItem("api_token")).then(response => {
          response.data.forEach(item => {
          let i = {
              id: item.id,
              label: item.name
          }
          this.options.push(i)
          
        })
      })
    },

    getUsers() {
      axios.get('api/user/all'+ '?token=' + localStorage.getItem("api_token")).then(response => {
          response.data.forEach(item => {
          let i = {
              id: item.id,
              label: item.name
          }
          this.options2.push(i)
          
        })
        console.log(this.options2)
      })
      
    },

    updateSigns(signs) {
      let ids = [];

      signs.forEach((sign) => {
            ids.push(sign.id);
      });

      this.deploy.signs = ids;
      console.log(this.deploy.signs)
    },

    updateUsers(users) {
      let ids = [];

      users.forEach((user) => {
            ids.push(user.id);
      });

      this.deploy.users = ids;
      console.log(this.deploy.users)
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
      this.submitted = false
      this.$v.$reset()
    },
    
    getEmptyForm () {
      return {

        name: '',
        notes: '',
        status: 0,
        options: [],
        options2: [],
        
      }
    },

   

    async load() {
        this.deploy = await this.getEmptyForm();
        await this.$nextTick() // waits for the next event tick before completeing function.
    },

    

    submit() {
      
      axios.post('api/deploy/insert', this.deploy)
        .then(response => {
            this.message = 'Successfully created sign deploy.';
            this.showAlert();

            this.deploy = this.getEmptyForm();

     
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
    },


  mounted() {
    this.getIVIS();
    this.getUsers();
  },
}
</script>
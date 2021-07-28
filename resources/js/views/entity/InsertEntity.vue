<template>
  <CCard style="width: 70%;">
    <CCardHeader>
      <CIcon name="cil-notes"/> Insert Entity
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
          :value.sync="entity.name"
          
          placeholder="Name"
          invalidFeedback="This is a required field and must be at least 1 character"
        />

        <CInput
          label="Address"
          horizontal
          :lazy="false"
          :value.sync="entity.address"
          
          placeholder="Address"
          invalidFeedback="This is a required field and must be at least 1 characters"
        />

        <CInput
          label="Phone"
          horizontal
          :lazy="false"
          :value.sync="entity.phone"
          
          placeholder="Phone"
          invalidFeedback="This is a required field and must be at least 1 characters"
        />

        <CInput
          label="Logo"
          horizontal
          :lazy="false"
          :value.sync="entity.logo"
          
          placeholder="Logo"
          invalidFeedback="This is a required field and must be at least 1 characters"
        />

        

        //TODO CInputFile
        <!-- <CInputFile
          label="Insert image"
          placeholder="Please upload a image"
          horizontal
          v-on:change="onImageChange"
        /> -->


        <CRow>
          <CCol sm="3">
          </CCol>
          <CCol sm="9">
            <input type="file" v-on:change="onImageChange">
          </CCol>
        </CRow>

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


export default {
  name: 'InsertViennaSign',
   
  data() {
    return {
      form: this.getEmptyForm(),
      horizontal: { label:'col-3', input:'col-9' },
      submitted: false,
      options: [],
      filename: '',
      entity: {
        name: '',
        logo: '',
        address: '',
        phone: '',
      },
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
    checkIfValid (fieldName) {
      const field = this.$v.form[fieldName]
      if (!field.$dirty) {
        return null
      } 
      return !(field.$invalid || field.$model === '')
    },

    /* submit () {
      if (this.isValid) {
        this.submitted = true
      }
    }, */

    validate () {
      console.log(this.entity)
      /* console.log(exampleFormControlFile1.value) */
      /* this.viennaSign.image=this.exampleFormControlFile1.value; */
      this.$v.$touch()
    },

    reset () {
      this.entity = this.getEmptyForm()
      this.submitted = false
      this.$v.$reset()
    },
    
    getEmptyForm () {
      return {
        name: '',
        logo: '',
        address: '',
        phone: '',
        
      }
    },

    async load() {
        this.entity = await this.getEmptyForm();
        await this.$nextTick() // waits for the next event tick before completeing function.
    },

    onImageChange(e) {
      let logo = e.target.files[0];
      this.entity.logo.name = image.name;
      this.createImage(image);
    },
    createImage(file) {
        let reader = new FileReader();
        reader.onload = (e) => {
          this.entity.logo.base64 = e.target.result;
        };
        reader.readAsDataURL(file);
    },

   /*  getCategories() {
      axios.get('/api/vienna/signscategories').then(response => {
        response.data.forEach(item => {
          let i = {
              value: item.id,
              label: item.category
          }
          this.options.push(i)
          
        })
      })
    }, */

    submit() {
      console.log(this.entity)
      let self = this;
      axios.post('api/entity/insert', this.entity)
        .then(response => {
           self.message = 'Successfully created Entity.';
            self.showAlert();
            this.entity = this.getEmptyForm();
            console.log(response);
            /* this.showSuccess = true;
            this.successMessage = 'Sign Created'; */
            this.submitted = true;
            
            
        })
        .catch(error => {
            console.log(error)
            /* if (error.response.data.errors.id) {
                this.successMessage = error.response.data.errors.id[0];
                this.showError = true;
            } else if (error.response.data.errors.name) {
                this.successMessage = error.response.data.errors.name[0];
                this.showError = true;
            } else if (error.response.data.errors.image) {
                this.successMessage = error.response.data.errors.image[0];
                this.showError = true;
            } */
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
    
  },
}
</script>
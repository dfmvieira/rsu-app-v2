<template>
  <CCard style="width: 70%;">
    <CCardHeader>
      <CIcon name="cil-notes"/> Insert Vienna Sign Category
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
          label="Category"
          horizontal
          :lazy="false"
          :value.sync="viennaSignCategory.category"
          
          placeholder="Category"
          invalidFeedback="This is a required field and must be at least 1 characters"
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


export default {
  name: 'InsertViennaSignCategory',
   
  data() {
    return {
      form: this.getEmptyForm(),
      horizontal: { label:'col-3', input:'col-9' },
      submitted: false,
      viennaSignCategory: {
        category: ''
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
        category: {
            required,
            minLength: minLength(2)
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
      console.log(this.viennaSignCategory)
      /* console.log(exampleFormControlFile1.value) */
      /* this.viennaSign.image=this.exampleFormControlFile1.value; */
      this.$v.$touch()
    },

    reset () {
      this.viennaSignCategory = this.getEmptyForm()
      this.submitted = false
      this.$v.$reset()
    },
    
    getEmptyForm () {
      return {
        category: ""
      }
    },
    async load() {
        this.viennaSignCategory = await this.getEmptyForm();
        await this.$nextTick() // waits for the next event tick before completeing function.
    },

   /*  onImageChange(e) {
      let image = e.target.files[0];
      this.viennaSign.image.name = image.name;
      this.createImage(image);
    }, */
   /*  createImage(file) {
        let reader = new FileReader();
        reader.onload = (e) => {
          this.viennaSign.image.base64 = e.target.result;
        };
        reader.readAsDataURL(file);
    }, */

 /*    getCategories() {
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
      console.log(this.viennaSignCategory)
      let self = this;
      axios.post('api/vienna/insertcategory?token=' + localStorage.getItem("api_token"), this.viennaSignCategory)
        .then(response => {
            console.log(response);
            self.message = 'Successfully created category.';
            self.showAlert();
            /* this.showSuccess = true;
            this.successMessage = 'Category Created'; */
            this.submitted = true;
            this.load();
            
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
    /* this.getCategories(); */
  },
}
</script>
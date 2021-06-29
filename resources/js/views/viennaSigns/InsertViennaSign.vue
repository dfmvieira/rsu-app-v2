<template>
  <CCard style="width: 70%;">
    <CCardHeader>
      <CIcon name="cil-notes"/> Insert Vienna Sign
    </CCardHeader>
    <CCardBody>
      <CForm>
        <CInput
          label="Vienna ID"
          horizontal
          :lazy="false"
          :value.sync="viennaSign.id"
          
          placeholder="Vienna ID"
          invalidFeedback="This is a required field and must be at least 1 characters"
        />

        <CInput
          label="Name"
          horizontal
          :lazy="false"
          :value.sync="viennaSign.name"
          
          placeholder="Name"
          invalidFeedback="This is a required field and must be at least 1 character"
        />

        <CSelect
          label="Select category"
          horizontal
          :value.sync="viennaSign.IDCategory"
          :options="options"
          placeholder="Please select category"
        />

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
      viennaSign: {
        id: '',
        name: '',
        image: {
          name: '',
          base64: '',
        },
        IDCategory: null,
      },
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
      console.log(this.viennaSign)
      /* console.log(exampleFormControlFile1.value) */
      /* this.viennaSign.image=this.exampleFormControlFile1.value; */
      this.$v.$touch()
    },

    reset () {
      this.viennaSign = this.getEmptyForm()
      this.submitted = false
      this.$v.$reset()
    },
    
    getEmptyForm () {
      return {

          id: '',
          name: '',
          image: {
            name: '',
            base64: '',
          },
          IDCategory: null,
        
      }
    },

    async load() {
        this.viennaSignCategory = await this.getEmptyForm();
        await this.$nextTick() // waits for the next event tick before completeing function.
    },

    onImageChange(e) {
      let image = e.target.files[0];
      this.viennaSign.image.name = image.name;
      this.createImage(image);
    },
    createImage(file) {
        let reader = new FileReader();
        reader.onload = (e) => {
          this.viennaSign.image.base64 = e.target.result;
        };
        reader.readAsDataURL(file);
    },

    getCategories() {
      axios.get('/api/vienna/signscategories').then(response => {
        response.data.forEach(item => {
          let i = {
              value: item.id,
              label: item.category
          }
          this.options.push(i)
          
        })
      })
    },

    submit() {
      console.log(this.viennaSign)
      axios.post('api/vienna/insertsign', this.viennaSign)
        .then(response => {
            this.viennaSign = this.getEmptyForm();
            console.log(response);
            this.showSuccess = true;
            this.successMessage = 'Sign Created';
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
  },

  mounted() {
    this.getCategories();
  },
}
</script>
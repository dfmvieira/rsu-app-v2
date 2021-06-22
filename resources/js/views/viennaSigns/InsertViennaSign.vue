<template>
  <CCard>
    <CCardHeader>
      <CIcon name="cil-notes"/> Insert Vienna Sign
      <!-- <a class="badge badge-danger" href="https://coreui.io/pro/vue/">CoreUI Pro</a> -->
      <!-- <div class="card-header-actions">
        <a 
          class="card-header-action" 
          href="https://github.com/vuelidate/vuelidate" 
          target="_blank" 
          rel="noreferrer noopener"
        >
          <small class="text-muted">docs</small>
        </a>
      </div> -->
    </CCardHeader>
    <CCardBody>
     <!--  <CLink 
        href="https://github.com/vuelidate/vuelidate" 
        target="_blank" 
        rel="noreferrer noopener"
      >
        Vuelidate
      </CLink> 
      provides <cite>Simple, lightweight model-based validation for Vue.js. </cite>
      In this view Vuelidate features are integrated with CoreUI Vue form components.
      <hr> -->
      <!-- <CRow> -->
        <!-- <CCol lg="6"> -->
          <!-- <h6>Simple Form</h6> -->
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

          <!--   <CInputFile
                label="Insert image"
                horizontal
                placeholder="Please upload a image"
                custom
                :value.sync="viennaSign.image"
              /> -->
              <!-- <CInputFile
                label="File input"
                horizontal
                :value.sync="viennaSign.image"
              /> -->

             <!--  <div class="form-group">
                <label for="exampleFormControlFile1">Example file input</label>
                <input type="file" class="form-control-file" id="exampleFormControlFile1">
              </div> -->
         
              <!-- <label for="customFile">Avatar</label>
              
                <div class="custom-file">
                
                  <input type="file" class="custom-file-input" id="customFile" 
                      ref="file" @change="handleFileObject()">
                  <label class="custom-file-label text-left" for="customFile">{{ filename }}</label>
                </div>

              <form @submit="formSubmit" enctype="multipart/form-data">
                  <input type="file" class="form-control" v-on:change="onChange">
                  <button class="btn btn-primary btn-block">Upload</button>
              </form>
             -->
              <input type="file" class="form-control" v-on:change="onChange">

<!-- :disabled="!isValid || submitted" -->

            
            <CButton 
              color="primary" 
              
              @click="submit"
            >
              Submit
            </CButton>
            <CButton 
              class="ml-1"  
              color="success" 
              :disabled="isValid"
              @click="validate"
            >
              Validate
            </CButton>
            <CButton 
              class="ml-1"
              color="danger"
              :disabled="!isDirty"
              @click="reset"
            >
              Reset
            </CButton>
          </CForm>
          <br/>
       <!--  </CCol> -->

        <!-- <CCol lg="6">
          <CCard :class="`bg-${submitted ? 'info' : 'secondary' }`">
            <pre>{{formString}}</pre>
          </CCard>
        </CCol> -->
     <!--  </CRow> -->
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
      file: '',
      viennaSign: {
        id: '',
        name: '',
        image: '',
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
      this.form = this.getEmptyForm()
      this.submitted = false
      this.$v.$reset()
    },
    
    getEmptyForm () {
      return {
        ViennaID: "",
        name: "",
        select: "",
        image: "",
      }
    },
    /* handleFileObject() {
      this.viennaSign.image = this.$refs.file.files[0]
      this.filename = this.viennaSign.name
    }, */
    onChange(e) {
      this.file = e.target.files[0];
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

          axios.post('api/vienna/insertsign', this.viennaSign)
              .then(response => {
                  this.showSuccess = true;
                  this.successMessage = 'Sign Created';
                  console.log("trabalha");
                  this.submitted = true;
                  this.getEmptyForm();
                  
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
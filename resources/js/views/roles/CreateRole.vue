<template>
  <CRow>
    <CCol col="12" lg="6">
      <CCard no-header>
        <CCardBody>
          <h3>
            Create Role
          </h3>
          
          <CInput label="Name" type="text" placeholder="Name" v-model="role.name"></CInput>

          <CButton color="primary" @click="store()">Create</CButton>
          <CButton color="primary" @click="goBack">Back</CButton>
        </CCardBody>

        
      </CCard>
    </CCol>
    <CToaster :autohide="3000">
            <template v-for="toast in fixedToasts">
                <CToast
                :key="'toast' + toast"
                :show="true"
                header="Info"
                style="max-height: 100px;"
                >
                    {{ toastMessage }}
                </CToast>
            </template>
        </CToaster>
  </CRow>
</template>

<script>
import axios from 'axios'
export default {
  name: 'CreateRole',
  data: () => {
    return {
        role: {
          name: '',
        },

        // TOAST
        toastMessage: '',
        fixedToasts: 0
    }
  },
  methods: {
    goBack() {
      this.$router.go(-1)
      // this.$router.replace({path: '/users'})
    },
    store() {
        axios.post(this.$apiAdress + '/api/roles?token=' + localStorage.getItem("api_token"), this.role)
        .then(response => {
            this.insertToast('Role has been Created!')
        }).catch(err => {
          console.log(err)
            /* if(error.response.data.message == 'The given data was invalid.'){
              self.message = '';
              for (let key in error.response.data.errors) {
                if (error.response.data.errors.hasOwnProperty(key)) {
                  self.message += error.response.data.errors[key][0] + '  ';
                }
              }
              self.showAlert();
            }else{
              console.log(error);
              self.$router.push({ path: 'login' }); 
            } */
        });
    },

    // Insert Toasts
    insertToast(message) {
      this.fixedToasts++
      this.toastMessage = message
    },
  },
  mounted: function(){
    let self = this;
    axios.get(  this.$apiAdress + '/api/roles/create?token=' + localStorage.getItem("api_token"))
    .then(function (response) {
        self.statuses = response.data;
    }).catch(function (error) {
        console.log(error);
        self.$router.push({ path: 'login' });
    });
  }
}

</script>

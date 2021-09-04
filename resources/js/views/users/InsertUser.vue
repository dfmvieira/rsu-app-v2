<template>
    <CCard no-header>
        <CCardBody>
          <CForm>
            <template slot="header">
              Edit User id:  {{ $route.params.id }}
            </template>
            <CAlert
              :show.sync="dismissCountDown"
              color="primary"
              fade
            >
              ({{dismissCountDown}}) {{ message }}
            </CAlert>
            <CInput type="text" label="Name" placeholder="Name" v-model="name"></CInput>
            <CInput type="text" label="Email" placeholder="Email" v-model="email"></CInput>
            <CSelect label="Entity" :options="entities"/>
            <CRow style="margin: 10px 10px 0px 0px; float: right">
              <CButton color="primary" @click="submit()">Save</CButton>
              <CButton class="ml-1" color="danger" @click="reset">Reset</CButton>
            </CRow>
          </CForm>
        </CCardBody>

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
      </CCard>
</template>


<script>
import axios from 'axios'
export default {
  name: 'EditUser',
  props: {
    caption: {
      type: String,
      default: 'User id'
    },
  },
  data() {
    return {
        name: '',
        email: '',
        showMessage: false,
        message: '',
        entities: [],
        dismissSecs: 7,
        dismissCountDown: 0,
        showDismissibleAlert: false,

        // TOAST
        toastMessage: '',
        fixedToasts: 0
    }
  },
  methods: {
    getEntities() {
      axios.get(this.$apiAdress + '/api/entity')
      .then (response => {
          response.data.forEach(item => {
              this.entities.push({value: item.id, label: item.name })
          });
          console.log(response.data)
      }).catch(function (error) {
          console.log(error);
      });
    },

    submit() {
      axios.post('/api/user/create?token' + localStorage.getItem("api_token"))
        .then(response => {
          
        }).catch(err => {
          console.log(err)
        })
    },

    // Insert Toasts
    insertToast(message) {
        this.fixedToasts++
        this.toastMessage = message
    },

  },
  mounted() {
    this.getEntities();
  }
}
</script>

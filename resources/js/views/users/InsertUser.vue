<template>
    <CCard style="width: 70%;">
      <CCardHeader>
        Insert a New User
      </CCardHeader>
      <CCardBody>
        <CForm>
          <CInput type="text" label="Name" placeholder="Name" v-model="form.name"></CInput>
          <CInput type="email" label="Email" placeholder="Email" v-model="form.email"></CInput>
          <CInput type="number" label="Phone" placeholder="Phone" v-model="form.phone"></CInput>
          <CInput type="password" label="Password" placeholder="Password" v-model="form.password"></CInput>
          <CSelect label="Entity" :value.sync="form.entity" :options="entities"/>
          <CSelect label="Role" :value.sync="form.role" :options="roles"/>
          <CRow style="margin: 10px 10px 0px 0px; float: right">
            <CButton color="danger" @click="reset()">Reset</CButton>
            <CButton class="ml-1" color="primary" @click="submit()">Save</CButton>
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
        form: { 
          name: '',
          phone: '',
          email: '',
          password: '',
          entity: null,
          role: null,
        },
       
        entities: [{
          value: 0,
          label: '----'
        }],
        roles: [{
            value: 0,
            label: '----'
          }],

        // TOAST
        toastMessage: '',
        fixedToasts: 0
    }
  },
  methods: {
    getEntities() {
      axios.get(this.$apiAdress + '/api/entity?token=' + localStorage.getItem("api_token"))
      .then(response => {
        response.data.forEach(item => {
          this.entities.push({value: item.id, label: item.name })
        });
      }).catch(function (error) {
        console.log(error);
      });
    },

    getRoles (){
      axios.get(this.$apiAdress + '/api/roles?token=' + localStorage.getItem("api_token") )
      .then(response => {
        response.data.forEach(item => {
          this.roles.push({value: item.name, label: item.name})
        })
      }).catch(function (error) {
        console.log(error);
      });
    },

    submit() {
      axios.post('/api/user/create?token=' + localStorage.getItem("api_token"), this.form)
        .then(response => {
          this.insertToast("User has been created!")
          this.reset()
        }).catch(err => {
          console.log(err)
        })
    },

    reset() {
      this.form.name = ''
      this.form.email = ''
      this.form.phone = ''
      this.form.password = ''
    },

    // Insert Toasts
    insertToast(message) {
        this.fixedToasts++
        this.toastMessage = message
    },

  },
  mounted() { 
    this.getEntities();
    this.getRoles();
  }
}
</script>

<template>
  <CRow>
    <CCol col="12" lg="6">
      <CCard no-header>
        <CCardBody>
          <CForm>
            <template slot="header">
              Edit User id:  {{ $route.params.id }}
            </template>

            <CInput type="text" label="Name" placeholder="Name" v-model="form.name"></CInput>
            <CInput type="text" label="Email" placeholder="Email" v-model="form.email"></CInput>
            <CInput type="number" label="Phone" placeholder="Phone" v-model="form.phone"></CInput>
            <CInput type="password" label="Password" placeholder="Password" v-model="form.password"></CInput>
            <CSelect label="Entity" :value.sync="form.entity" :options="entities"/>
            <CSelect label="Role" :value.sync="form.role" :options="roles"/>
            <CButton color="primary" @click="update()">Save</CButton>
            <CButton color="primary" @click="goBack">Back</CButton>
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
    </CCol>
  </CRow>
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
          email: '',
          phone: '',
          password: '',
          role: '',
          entity: ''
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
    goBack() {
      this.$router.go(-1)
      // this.$router.replace({path: '/users'})
    },
    update() {
        let self = this;
        axios.put(  this.$apiAdress + '/api/users/' + self.$route.params.id + '?token=' + localStorage.getItem("api_token"), this.form)
        .then(function (response) {
            self.insertToast('Successfully updated user!')
            self.goBack()
        }).catch(function (error) {
            console.log(error);
            self.$router.push({ path: '/login' });
        });
    },

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

    // Insert Toasts
    insertToast(message) {
        this.fixedToasts++
        this.toastMessage = message
    },
  },
  mounted: function(){
    let self = this;
    axios.get(  this.$apiAdress + '/api/users/' + self.$route.params.id + '/edit?token=' + localStorage.getItem("api_token"))
    .then(function (response) {
      self.form = response.data
      self.form.password = '';
    }).catch(function (error) {
        console.log(error);
        self.$router.push({ path: '/login' });
    });

    self.getEntities()
    self.getRoles()
  }
}


</script>

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
            <CButton color="primary" @click="update()">Save</CButton>
          </CForm>
        </CCardBody>
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
        showDismissibleAlert: false
    }
  },
  methods: {
    getEntities() {
      let self = this;
      axios.get(this.$apiAdress + '/api/entity')
      .then (response => {
          response.data.forEach(item => {
              this.entities.push({value: item.id, label: item.name })
          });
          console.log(response.data)
      }).catch(function (error) {
          console.log(error);
      });
    }

    /* submit() {
      axios.post(this.$$apiAdress + '/api/user/create') 
    } */

  },
  mounted() {
    self.getEntities();
  }
}
</script>

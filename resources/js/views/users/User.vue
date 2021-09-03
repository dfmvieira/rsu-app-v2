<template>
  <CRow>
    <CCol col="12" lg="6">
      <CCard>
        <CCardHeader>
          <slot name='header'>
                User
            </slot>
        </CCardHeader>
        <CCardBody>
          <CForm>
            <CRow>
              <CCol md="8" >
                <label><b>ID: </b> {{ user.id }}</label> <br><br>
                <label><b>Name: </b> {{ user.name }}</label> <br><br>
                <label><b>Email: </b> {{ user.email }}</label> <br><br>
                <label><b>Roles: </b> {{ user.menuroles }}</label> <br><br>
                <label><b>Entity: </b> {{ user.IDEntity }}</label> <br><br>
              </CCol> 
            </CRow>
          </CForm>
        </CCardBody>
      </CCard>
    </CCol>
  </CRow>
</template>

<script>
import axios from 'axios'
export default {
  name: 'User',
  data: () => {
    return {
       user: {
                id: '',
                name: '',
                email: '',
                menuroles: '',
                IDEntity: '',
            },
    }
  },
  methods: {

    getUserInfo() {
      let self = this;
      axios.get('api/user/logged?token=' + localStorage.getItem("api_token"))
      .then(function (response) {
        self.user = response.data
      }).catch(function (error) {
        console.log(error);
        self.$router.push({ path: '/login' })
      });
    },
    /* getUserData (id) {
      const user = usersData.find((user, index) => index + 1 == id)
      const userDetails = user ? Object.entries(user) : [['id', 'Not found']]
      return userDetails.map(([key, value]) => { return { key, value } })
    }, */
    goBack() {
      this.$router.go(-1)
    }
  },
  mounted: function(){
    /* let self = this;
    axios.get(  this.$apiAdress + '/api/users/' + self.$route.params.id + '?token=' + localStorage.getItem("api_token"))
    .then(function (response) {
      const items = Object.entries(response.data);
      self.items = items.map(([key, value]) => {return {key: key, value: value}});
    }).catch(function (error) {
      console.log(error);
      self.$router.push({ path: '/login' });
    }); */
    this.getUserInfo()
  }
}
</script>

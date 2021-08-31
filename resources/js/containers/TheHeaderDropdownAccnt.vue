<template>
  <CDropdown
    inNav
    class="c-header-nav-items"
    placement="bottom-end"
    add-menu-classes="pt-0"
  >
    <template #toggler>
      <CHeaderNavLink>
        <div class="c-avatar" style="margin-right: 20px">
          {{ name }}
        </div>
       
      </CHeaderNavLink>
    </template>
    <CDropdownItem>
      <CIcon name="cil-user" /> Profile
    
    </CDropdownItem>
    <CDropdownItem @click="logout()">
      <CIcon name="cil-lock-locked" /> Logout
    </CDropdownItem>
  </CDropdown>
</template>

<script>
import axios from 'axios'
export default {
  name: 'TheHeaderDropdownAccnt',
  data () {
    return {
      itemsCount: 42,
      name: '',
    }
  },
  methods:{
    logout(){
      let self = this;
      axios.post( this.$apiAdress + '/api/logout?token=' + localStorage.getItem("api_token"),{})
      .then(function (response) {
        localStorage.setItem('roles', '');
        self.$router.push({ path: '/login' });
      }).catch(function (error) {
        console.log(error); 
      });
    },

    getUserInfo() {
      let self = this;
      axios.get('api/user/logged?token=' + localStorage.getItem("api_token"))
      .then(function (response) {
        self.name = response.data.name
      }).catch(function (error) {
        console.log(error);
        self.$router.push({ path: '/login' })
      });
    }

  },
  mounted() { 
    this.getUserInfo()
  }
}
</script>

<style scoped>
  .c-icon {
    margin-right: 0.3rem;
  }
</style>

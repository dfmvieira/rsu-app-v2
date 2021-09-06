<template>
  <CRow>
    <CCol col="12" xl="12">
      <transition name="slide">
      <CCard>
        <CCardHeader>
            Users
        </CCardHeader>
        <CCardBody>
          <CDataTable
            hover
            striped
            :items="items"
            :fields="fields"
            column-filter
            table-filter
            items-per-page-select
            :items-per-page="10"
            sorter
            pagination
          >
          <template #show_details="{item, index}">
              <td class="py-2">
              <CButton
                  color="primary"
                  variant="outline"
                  square
                  size="sm"
                  @click="toggleDetails(index)"
              >
                  {{details.includes(index) ? 'Hide' : 'Show'}}
              </CButton>
              </td>
          </template>
          <template #details="{item, index}">
            <CCollapse :show="details.includes(index)">
                <CCardBody>
                  <b>User id:</b>  {{item.id}} <br><br>
                  <b>Name:</b>  {{item.name}} <br><br>
                  <b>Email:</b>  {{item.email}} <br><br>
                  <b>Phone:</b>  {{item.phone}} <br><br>
                  <b>User roles:</b>  {{item.roles}} <br><br>
                  <b>User entity:</b>  {{item.entity}} <br><br>

                 <!--  <CButton color="primary" @click="showUser( item.id )">Show</CButton> -->
                  
                  <CButton color="primary" @click="editUser( item.id )">Edit</CButton>

                  <CButton v-if="you!=item.id" color="danger" @click="deleteUser( item.id )">Delete</CButton>

                </CCardBody>
            </CCollapse>
          </template>
        </CDataTable>
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
      </transition>
    </CCol>
  </CRow>
</template>

<script>
import axios from 'axios'

export default {
  name: 'Users',
  data: () => {
    return {
      items: [],
      fields: [
        'id', 
        'name', 
        'email',
        'phone',
        'registered', 
        'roles', 
        'entity', 
        /* 'show', 
        'edit', 
        'delete', */
        { 
            key: 'show_details', 
            label: 'Options', 
            _style: 'width:1%', 
            sorter: false, 
            filter: false
        }
      ],
      details: [],
      currentPage: 1,
      perPage: 5,
      totalRows: 0,
      you: null,

      // TOAST
      toastMessage: '',
      fixedToasts: 0
    }
  },
  paginationProps: {
    align: 'center',
    doubleArrows: false,
    previousButtonHtml: 'prev',
    nextButtonHtml: 'next'
  },
  methods: {
    getBadge (status) {
      return status === 'Active' ? 'success'
        : status === 'Inactive' ? 'secondary'
          : status === 'Pending' ? 'warning'
            : status === 'Banned' ? 'danger' : 'primary'
    },
    toggleDetails (index) {
        const position = this.details.indexOf(index)
        position !== -1 ? this.details.splice(position, 1) : this.details.push(index)
    },
    userLink (id) {
      return `users/${id.toString()}`
    },
    editLink (id) {
      return `users/${id.toString()}/edit`
    },
    showUser ( id ) {
      const userLink = this.userLink( id );
      this.$router.push({path: userLink});
    },
    editUser ( id ) {
      const editLink = this.editLink( id );
      this.$router.push({path: editLink});
    },
    deleteUser ( id ) {
      let self = this;
      let userId = id;
      axios.post(  this.$apiAdress + '/api/users/' + id + '?token=' + localStorage.getItem("api_token"), {
        _method: 'DELETE'
      })
      .then(function (response) {
          self.insertToast('Successfully deleted user.');
          self.getUsers();
      }).catch(function (error) {
        console.log(error);
        self.$router.push({ path: '/login' });
      });
    },
    countDownChanged (dismissCountDown) {
      this.dismissCountDown = dismissCountDown
    },
    showAlert () {
      this.dismissCountDown = this.dismissSecs
    },
    getUsers (){
      let self = this;
      axios.get(  this.$apiAdress + '/api/users?token=' + localStorage.getItem("api_token"))
      .then(function (response) {
        self.items = response.data.users;
        self.you = response.data.you;
      }).catch(function (error) {
        console.log(error);
        self.$router.push({ path: '/login' });
      });
    },

    // Insert Toasts
    insertToast(message) {
        this.fixedToasts++
        this.toastMessage = message
    },
  },
  mounted: function(){
    this.getUsers();
    
  }
}
</script>

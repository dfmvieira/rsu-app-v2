<template>
  <CCardBody>
    <CDataTable
      :items="usersData"
      :fields="fields"
      column-filter
      table-filter
      items-per-page-select
      :items-per-page="5"
      hover
      sorter
      pagination
    >
      <template #status="{item}">
        <td>
          <CBadge :color="getBadge(item.status)">
            {{item.status}}
          </CBadge>
        </td>
      </template>
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
            <CMedia :aside-image-props="{ height: 102 }">
              <h4>
                {{item.username}}
              </h4>
              <p class="text-muted">User since: {{item.registered}}</p>
              <CButton size="sm" color="info" class="">
                User Settings
              </CButton>
              <CButton size="sm" color="danger" class="ml-1">
                Delete
              </CButton>
            </CMedia>
          </CCardBody>
        </CCollapse>
      </template>
    </CDataTable>
  </CCardBody>
</template>

<script>
import usersData from '../users/UsersData'

const fields = [
  { key: 'username', _style:'width:40%' },
  'registered',
  { key: 'role', _style:'width:20%;' },
  { key: 'status', _style:'width:20%;' },
  { 
    key: 'show_details', 
    label: '', 
    _style: 'width:1%', 
    sorter: false, 
    filter: false
  }
]

export default {
  name: 'AdvancedTables',
  data () {
    return {
      usersData,
      fields,
      details: []
    }
  },
  methods: {
    getBadge (status) {
      switch (status) {
        case 'Active': return 'success'
        case 'Inactive': return 'secondary'
        case 'Pending': return 'warning'
        case 'Banned': return 'danger'
        default: 'primary'
      }
    },
    toggleDetails (index) {
      const position = this.details.indexOf(index)
      position !== -1 ? this.details.splice(position, 1) : this.details.push(index)
    }
  }
}
</script>
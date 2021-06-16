<template>
    <CCard>
        <CCardHeader>
            <slot name='header'>
                <CIcon name="cil-grid"/> ola sou eu
            </slot>
        </CCardHeader>
        <CCardBody>
            <CDataTable
            :items="signs"
            :fields="fields"
            column-filter
            table-filter
            items-per-page-select
            :items-per-page="5"
            hover
            sorter
            pagination
            >
            <!-- <template #status="{item}">
                <td>
                <CBadge :color="getBadge(item.status)">
                    {{item.status}}
                </CBadge>
                </td>
            </template> -->
            <!-- <template #show_details="{item, index}">
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
            </template> -->
            <!-- <template #details="{item, index}">
                <CCollapse :show="details.includes(index)">
                <CCardBody>
                    <CMedia :aside-image-props="{ height: 102 }">
                    <h4>
                        {{item.id}}
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
            </template> -->
            </CDataTable>
        </CCardBody>
    </CCard>
</template>

<script>
import axios from 'axios'
export default {
    name: 'ViennaSigns',
    data () {
    return {
        signs: [],
    }
  },
    props: {
        fields: {
            type: Array,
            default () {
                ['ID', 'Name', 'Category'] 
            }
        }
    },
    methods: {
        getViennaSigns() {
            axios.get('api/vienna').then(response => {
                console.log(response.data)
                this.signs=response.data
                
            })
        },
     
    },
    mounted() {
        
        this.getViennaSigns();
    }
}
</script>
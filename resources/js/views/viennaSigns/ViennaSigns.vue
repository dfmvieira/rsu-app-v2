<template>
    <CCard>
        <CCardHeader>
            <slot name='header'>
                <CIcon name="cil-grid"/> Vienna Signs
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
                        
                        <CMedia :aside-image-props="{src:item.image, height: 102 }">
                        <h4>
                            {{item.name}}
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
    </CCard>
</template>

<script>
import axios from 'axios'
export default {
    name: 'ViennaSigns',
    data () {
        return {
            signs: [],
            fields: [
                {key: 'id', label: 'ID'},
                {key: 'name', label: 'Name'}, 
                {key: 'category', label: 'Category'},  
                { 
                    key: 'show_details', 
                    label: '', 
                    _style: 'width:1%', 
                    sorter: false, 
                    filter: false
                }
            ],
            details: [],
        }
    },
    methods: {
        getViennaSigns() {
            axios.get('api/vienna').then(response => {
                console.log(response.data)
                this.signs=response.data
                console.log(this.signs)
                
            })
        },
        
        toggleDetails (index) {
            const position = this.details.indexOf(index)
            position !== -1 ? this.details.splice(position, 1) : this.details.push(index)
        }
     
    },
    mounted() {
        
        this.getViennaSigns();
        console.log("ola");
        /* console.log(this.signs); */
    }
}
</script>
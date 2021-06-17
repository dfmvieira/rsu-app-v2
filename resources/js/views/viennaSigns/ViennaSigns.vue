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
                        
                        <CMedia :aside-image-props="{src:item.image, height: 120 ,width: 120}">
                        <h4>
                            {{item.name}}
                        </h4>
                        <p class="text-muted">Type: {{item.category}}</p>
                        <!-- <CButton size="sm" color="info" class="">
                            User Settings
                        </CButton> -->
                        <CButton size="sm" color="danger" class="ml-1" data-toggle="modal" data-target="#exampleModalCenter">
                            Delete
                        </CButton>
                        <CButton type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">
                            modal
                        </CButton>
                            <!-- Button trigger modal -->
                            

                            
                        </CMedia>
                    </CCardBody>
                </CCollapse>
            </template>
            </CDataTable>
        </CCardBody>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">
            Launch demo modal
        </button>
        <!-- Modal -->
        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                ...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
            </div>
        </div>
        </div>
    
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
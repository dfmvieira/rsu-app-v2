<template>
    <CCard>
        <CCardHeader>
            <slot name='header'>
                Entities
            </slot>
        </CCardHeader>
        <CCardBody>
            <CDataTable
            :items="entities"
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
                    <CForm>
                        <CRow style="margin-top: 20px">
                            <CCol lg="4" >
                                <div style="padding: 0px 30px 30px 30px">
                                    <CMedia :aside-image-props="{src:item.logo, height: 170 ,width: 170}"> </CMedia>
                                </div>
                            </CCol>
                            <CCol md="8" >

                                <label><b>Name: </b> {{ item.name }}</label><br><br>
                                <label><b>Address: </b> {{ item.address }}</label><br><br>
                                <label><b>Phone: </b> {{ item.phone }}</label><br><br>

                            <!-- <CInput
                                    label="Name"
                                    horizontal
                                    :lazy="false"
                                    :value.sync="item.name"
                                    
                                    
                                    invalidFeedback="This is a required field and must be at least 1 character"
                                />
                                <CInput
                                    label="Address"
                                    horizontal
                                    :lazy="false"
                                    :value.sync="item.address"
                            
                                
                                    invalidFeedback="This is a required field and must be at least 1 character"
                                />
                                <CInput
                                    label="Phone"
                                    horizontal
                                    :lazy="false"
                                    :value.sync="item.phone"
                                
                                
                                    invalidFeedback="This is a required field and must be at least 1 character"
                                />  -->
                                <CButton
                                    @click="updateModalHandler(item)"
                                    size="sm" 
                                    color="primary"
                                    >
                                    Editar
                                </CButton>

                                <CModal 
                                :show.sync="showEditModal"
                                :closeOnBackdrop=false
                                size="lg"
                                >
                                    <template #header>
                                        <h5>Entity</h5>
                                    </template>
                                    <CRow>
                                        <CCol lg=3>
                                            <CMedia :aside-image-props="{src:editEntity.logo, height: 170 ,width: 170}"> </CMedia>
                                        </CCol>
                                        <CCol lg=9>
                                            <CInput
                                                label="Name"
                                                horizontal
                                                :lazy="false"
                                                :value.sync="editEntity.name"
                                                
                                                placeholder="Name"
                                                invalidFeedback="This is a required field and must be at least 1 character"
                                            />

                                            <CInput
                                                label="Address"
                                                horizontal
                                                :lazy="false"
                                                :value.sync="editEntity.address"
                                                
                                                placeholder="Address"
                                                invalidFeedback="This is a required field and must be at least 1 characters"
                                            />

                                            <CInput
                                                label="Phone"
                                                horizontal
                                                :lazy="false"
                                                :value.sync="editEntity.phone"
                                                type="number"
                                                
                                                placeholder="Phone"
                                                invalidFeedback="This is a required field and must be at least 1 characters"
                                            />
                                        </CCol>
                                    </CRow>

                                    <template #footer>
                                        <CButton @click="showEditModal = false;" color="danger">Discard</CButton>
                                        <CButton @click="update" color="success">Accept</CButton>
                                    </template>
                                </CModal>
                            </CCol> 
                        </CRow>
                    </CForm>
                </CCollapse>
            </template>
            </CDataTable>
        </CCardBody>
    </CCard>
</template>

<script>
import axios from 'axios'

import InsertEntity from './InsertEntity.vue'

export default {
    name: 'ViennaSigns',
    components: {
        "insert-entity": InsertEntity,
    },
    data () {
        return {
            entities: [],
            editEntity: {
                id: '',
                name: '',
                address: '',
                phone: '',
                logo: ''
            },
            fields: [
                {key: 'id', label: 'ID'},
                {key: 'name', label: 'Name'},  
                {key: 'address', label: 'Address'},
                {key: 'phone', label: 'Phone'},
                { 
                    key: 'show_details', 
                    label: 'Options', 
                    _style: 'width:1%', 
                    sorter: false, 
                    filter: false
                }
    
            ],
            details: [],
            editModal: false,
            entity: {
                id: '',
                name: '',
                logo: '',
                address: '',
                phone: '',
                _method:"patch"
            },
            message: '',

            showEditModal: false
        

        }
    },
    methods: {
        getEntities() {
            axios.get('api/entity?token=' + localStorage.getItem("api_token")).then(response => {
                this.entities = response.data
                this.entity = this.entities[0]
            })
        },
        
        toggleDetails (index) {
            const position = this.details.indexOf(index)
            position !== -1 ? this.details.splice(position, 1) : this.details.push(index)
        },

        updateModalHandler(entity) {
            this.showEditModal = true
            this.editEntity.id = entity.id
            this.editEntity.name = entity.name
            this.editEntity.address = entity.address
            this.editEntity.phone = entity.phone
            this.editEntity.logo = entity.logo
        },

            
        update(){
            axios.put('api/entity/' + this.editEntity.id + '?token=' + localStorage.getItem("api_token"), this.editEntity)
                .then(response => {
                    this.message = 'Successfully updated Entity.';

                    this.showEditModal = false
                    this.getEntities()
                })
                .catch(err =>{
                    console.log(err)
            })
        },


    },
    mounted() {
        this.getEntities();
        
    }
}
</script>
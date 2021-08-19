<template>
    <CCard>
        <CCardHeader>
            <slot name='header'>
                Sign Publication
            </slot>
        </CCardHeader>
        <CCardBody>
            <CAlert
              :show.sync="dismissCountDown"
              color="success"
              fade
            >
              ({{dismissCountDown}}) {{ message }}
            </CAlert>
            <CDataTable
            :items="ivis"
            :fields="fields"
            column-filter
            table-filter
            items-per-page-select
            :items-per-page="5"
            hover
            sorter
            pagination
            >
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
            <template #details="{item, index}">
                <CCollapse :show="details.includes(index)">
                    <CForm>
                        <CRow>
                            <CCol lg="4" >
                                <CMedia :aside-image-props="{src:item.logo, height: 200 ,width: 200}"> </CMedia>
                            </CCol>
                            <CCol md="8" >
                            <CInput
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
                                /> 
                                <CButton
                                    @click="update()"
                                    size="sm" 
                                    color="primary"
                                    >
                                    Guardar
                                </CButton>
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
export default {
    name: 'ViennaSigns',
    data () {
        return {
            ivis: [],
            fields: [
                {key: 'id', label: 'ID'},
                {key: 'name', label: 'Name'},  
                {key: 'GUID', label: 'Address'},
                {key: 'viennaID', label: 'Phone'},
                {key: 'status', label: 'Status'},
                {key: 'comment', label: 'Comment'},
                {key: 'latitude', label: 'Latitude'},              
                {key: 'longitude', label: 'Longitude'},              
            ],
            details: [],
            editModal: false,
            ivi: {
                id: '',
                name: '',
                GUID: '',
                viennaID: '',
                status: '',
                coordinates:{
                    lat: '',
                    lng: ''
                },
                /* _method:"patch" */
            },
            showMessage: false,
            message: '',
            dismissSecs: 7,
            dismissCountDown: 0,
            showDismissibleAlert: false,

        }
    },
    methods: {
        getIVIS() {
            axios.get('api/ivisign'+ '?token=' + localStorage.getItem("api_token")).then(response => {
                console.log(response.data)
                this.ivis=response.data
                this.ivi=this.ivis[0]
                console.log(this.ivis)
            })
        },
        
        toggleDetails (index) {
            const position = this.details.indexOf(index)
            position !== -1 ? this.details.splice(position, 1) : this.details.push(index)
        },
            
        update(entity){
            let self = this;
           axios.put(`api/entity/1`, this.entity)
               .then(response=>{
                console.log("sucess")
                self.message = 'Successfully updated Entity.';
                self.showAlert();
               /*  this.loadViennaSigns() */

                    
               })
               .catch(()=>{
                  console.log("Error.....")
               })
        },
 /*        async loadViennaSigns() {
            this.signs = await this.getEntities();
            this.viennaSign = await this.getEmptyForm();
            await this.$nextTick() // waits for the next event tick before completeing function.
        }, */

        countDownChanged (dismissCountDown) {
            this.dismissCountDown = dismissCountDown
        },
        showAlert () {
            this.dismissCountDown = this.dismissSecs
        },
    
    },
    mounted() {
        this.getIVIS();
        
    }
}
</script>
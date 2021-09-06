<template>
    <CCard>
        <CCardHeader>
            <slot name='header'>
                <CIcon name="cil-grid"/> Vienna Signs
            </slot>
        </CCardHeader>
        <CCardBody>
            <CAlert
              :show.sync="dismissCountDown"
              color="primary"
              fade
            >
              ({{dismissCountDown}}) {{ message }}
            </CAlert>
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
                        <CButton
                            @click="warningModal = true"
                            size="sm" 
                            color="danger"
                        >
                            Delete
                        </CButton>
                        <CButton
                            @click="editModal = true"
                            size="sm" 
                            color="primary"
                            >
                            Edit
                        </CButton>
                        <CModal
                            title="Delete sign?!"
                            color="danger"
                            :show.sync="warningModal"
                            :centered = true
                        >
                            By deleting this sign, you will delete all the jobs and results that
                            are related to this sign.<br><br>
                            <span class="font-weight-bold">Are you sure you want to delete this sign?</span>
                            <template #footer>
                                <CButton @click="warningModal = false; toggleDetails(index); loadViennaSigns()" color="primary-color">Cancel</CButton>
                                <CButton @click=" deleteSigns(item); warningModal = false; toggleDetails(index); loadViennaSigns()" color="danger">Delete</CButton>
                            </template>
                        </CModal>
                        <CModal
                            title="Edit Vienna Sign"
                            color="primary"
                            :show.sync="editModal"
                            :centered = true
                        >
                            <CForm @submit.prevent="update">
                                <CInput
                                    label="ID"
                                    horizontal
                                    :lazy="false"
                                    :value.sync="viennaSign.id"
                                    placeholder="ID"
                                    invalidFeedback="This is a required field and must be at least 1 character"
                                />
                                <CInput
                                    label="Name"
                                    horizontal
                                    :lazy="false"
                                    :value.sync="viennaSign.name"
                                    placeholder="Name"
                                    invalidFeedback="This is a required field and must be at least 1 character"
                                />
                                <CSelect
                                    label="Select category"
                                    horizontal
                                    :value.sync="viennaSign.IDCategory"
                                    :options="options"
                                    placeholder="Please select category"
                                    />
                            </CForm>
                                   
                            <template #footer>
                                <CButton @click="editModal = false; toggleDetails(index); loadViennaSigns() " color="primary-color">Cancel</CButton>
                                <CButton @click=" update(item); editModal = false; toggleDetails(index); loadViennaSigns()" color="success">Edit</CButton>
                            </template>
                        </CModal>
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
                    label: 'Options', 
                    _style: 'width:1%', 
                    sorter: false, 
                    filter: false
                }
            ],
            details: [],
            warningModal: false,
            editModal: false,
            viennaSign: {
                id: '',
                name: '',
                IDCategory: '',
                _method:"patch"
            },
            options: [],
            showMessage: false,
            message: '',
            dismissSecs: 7,
            dismissCountDown: 0,
            showDismissibleAlert: false,

        }
    },
    methods: {
        getViennaSigns() {
            axios.get('api/vienna?token=' + localStorage.getItem("api_token")).then(response => {
                this.signs=response.data
            })
        },
        
        toggleDetails (index) {
            const position = this.details.indexOf(index)
            position !== -1 ? this.details.splice(position, 1) : this.details.push(index)
        },

        deleteSigns(item) {
            let self = this;
            axios.delete(`api/vienna/${item.id}?token=` + localStorage.getItem("api_token"))
                .then(res => {
                    if (res.data === 'ok')
                        self.message = 'Successfully deleted sign.';
                        self.showAlert();
                        console.log("sucess")
                        /* this.loadViennaSigns(); */
                }).catch(err => {
                console.log(err)
            })         
            
        },
        update(item){
            let self = this;
           axios.put(`api/vienna/${item.id}?token=` + localStorage.getItem("api_token"), this.viennaSign)
               .then(response=>{
                console.log("sucess")
                self.message = 'Successfully updated sign.';
                self.showAlert();
                this.loadViennaSigns()

                    
               })
               .catch(()=>{
                  console.log("Error.....")
               })
        },
        getEmptyForm () {
            return {
                viennaSign: {
                id: '',
                name: '',
                IDCategory: '',
                }
            }
        },
        async loadViennaSigns() {
            this.signs = await this.getViennaSigns();
            this.viennaSign = await this.getEmptyForm();
            await this.$nextTick() // waits for the next event tick before completeing function.
        },
        getCategories() {
            axios.get('/api/vienna/signscategories?token=' + localStorage.getItem("api_token")).then(response => {
                response.data.forEach(item => {
                let i = {
                    value: item.id,
                    label: item.category
                }
                this.options.push(i)
                
                })
            })
        },
        countDownChanged (dismissCountDown) {
            this.dismissCountDown = dismissCountDown
        },
        showAlert () {
            this.dismissCountDown = this.dismissSecs
        },
     
    },
    mounted() {
        this.getCategories();
        this.getViennaSigns();
    }
}
</script>
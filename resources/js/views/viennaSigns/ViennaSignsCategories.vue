<template>
    <CCard>
        <CCardHeader>
            <slot name='header'>
                <CIcon name="cil-grid"/> Vienna Signs Categories
            </slot>
        </CCardHeader>
        <CCardBody>
            <CDataTable
            :items="categories"
            :fields="fields"
            column-filter
            table-filter
            items-per-page-select
            :items-per-page="10"
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
               <!--  <td class="py-2"> -->
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
                            title="Delete category?!"
                            color="danger"
                            :show.sync="warningModal"
                            :centered = true
                        >
                            By deleting this category, you will delete all the jobs and results that
                            are related to this category.<br><br>
                            <span class="font-weight-bold">Are you sure you want to delete this category?</span>
                            <template #footer>
                                <CButton @click="warningModal = false; toggleDetails(index); loadViennaCategories()" color="primary-color">Cancel</CButton>
                                <CButton @click=" deleteCategories(item); warningModal = false; toggleDetails(index); loadViennaCategories()" color="danger">Delete</CButton>
                            </template>
                        </CModal>
                        <CModal
                            title="Edit category"
                            color="primary"
                            :show.sync="editModal"
                            :centered = true
                        >
                            <!-- <form @submit.prevent="update">
                                <div class="row">
                                    <div class="col-12 mb-2">
                                        <div class="form-group">
                                            <label>Category</label>
                                            <input type="text" class="form-control" v-model="categories.category">
                                        </div>
                                    </div>
                                </div>                        
                            </form> -->
                            <CForm @submit.prevent="update">
                                <CInput
                                    label="Categorie"
                                    horizontal
                                    :lazy="false"
                                    :value.sync="viennaSignCategory.category"
                                    
                                    placeholder="Category"
                                    invalidFeedback="This is a required field and must be at least 1 character"
                                />
                            </CForm>
                                   
                            <template #footer>
                                <CButton @click="editModal = false; toggleDetails(index); loadViennaCategories() " color="primary-color">Cancel</CButton>
                                <CButton @click=" update(item); editModal = false; toggleDetails(index); loadViennaCategories()" color="success">Edit</CButton>
                            </template>
                        </CModal>
                <!-- </td> -->
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
    name: 'ViennaSignsCategories',
    data () {
        return {
            categories: [],
            fields: [
                {key: 'id', label: 'ID'},
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
            viennaSignCategory: {
                category: '',
                _method:"patch"
            },

        }
    },
   
    methods: {
        getViennaCategories() {
            axios.get('api/vienna/signscategories').then(response => {
                this.categories=response.data
            })
        },
        async loadViennaCategories() {
            this.categories = await this.getViennaCategories();
            this.viennaSignCategory = await this.getEmptyForm();
            await this.$nextTick() // waits for the next event tick before completeing function.
        },
        toggleDetails (index) {
            const position = this.details.indexOf(index)
            position !== -1 ? this.details.splice(position, 1) : this.details.push(index)
        },
        deleteCategories(item) {
            console.log(item)

            axios.delete(`api/vienna/categories/${item.id}`)
                .then(res => {
                        if (res.data === 'ok')
                             console.log("sucess")
                             this.loadViennaCategories()
                    }).catch(err => {
                    console.log(err)
            })
            
        },
        getEmptyForm () {
            return {
                viennaSignCategory: {
                    category: ''
                },
            }
        },
        update(item){
            
           axios.put(`api/vienna/categories/${item.id}`, this.viennaSignCategory)
               .then(response=>{
                console.log("sucess")
              
                this.loadViennaCategories()

                    
               })
               .catch(()=>{
                  console.log("Error.....")
               })
        },
         /* update(item){
            console.log(this.viennaSignCategory)
            console.log(item.id)
            axios.put(`api/vienna/categories/${item.id}`, this.viennaSignCategory).then(response=>{
                console.log("sucess")
                this.getViennaCategories();
            }).catch(error=>{
                console.log(error)
            })
        }, */
    },
    mounted() {
        this.getViennaCategories()
    }
}
</script>
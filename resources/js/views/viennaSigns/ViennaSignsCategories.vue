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
                    @click="warningModal = true"
                    size="sm" 
                    color="danger"
                    >
                    Delete
                </CButton>

                <CModal
                    title="Delete sign?!"
                    color="danger"
                    :show.sync="warningModal"
                    :centered = true
                >
                    By deleting this category, you will delete all the jobs and results that
                    are related to this category.<br><br>
                    <span class="font-weight-bold">Are you sure you want to delete this category?</span>
                    <template #footer>
                        <CButton @click="warningModal = false" color="primary-color">Cancel</CButton>
                        <CButton @click=" deleteCategories(index); warningModal = false;" color="danger">Delete</CButton>
                    </template>
                </CModal>
                </td>
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
                    label: '', 
                    _style: 'width:1%', 
                    sorter: false, 
                    filter: false
                }
            ],
            details: [],
            warningModal: false,

        }
    },
    /* props: {
        items: Array,
        fields: {
            type: Array,
            default () {
                ['Name', 'Image', 'Category'] 
            }
        }
    }, */
    methods: {
        getViennaCategories() {
            axios.get('api/vienna/signscategories').then(response => {
                this.categories=response.data
            })
        },
        deleteCategories(item) {
            console.log(item)

            /* axios.delete(`api/vienna/categories/${item.id}`)
                .then(res => {
                        if (res.data === 'ok')
                             console.log("sucess")
                    }).catch(err => {
                    console.log(err)
            }) */
            this.getViennaCategories();
        },
    },
    mounted() {
        this.getViennaCategories()
    }
}
</script>
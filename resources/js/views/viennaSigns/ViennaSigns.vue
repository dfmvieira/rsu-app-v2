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
                            By deleting this sign, you will delete all the jobs and results that
                            are related to this sign.<br><br>
                            <span class="font-weight-bold">Are you sure you want to delete this sign?</span>
                            <template #footer>
                                <CButton @click="warningModal = false" color="danger">Discard</CButton>
                                <CButton @click=" deleteSigns(item); warningModal = false;" color="success">Accept</CButton>
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
        },

        deleteSigns(item) {
            axios.delete(`api/vienna/${item.id}`)
                .then(res => {
                        if (res.data === 'ok')
                             console.log("sucess")
                    }).catch(err => {
                    console.log(err)
            })
            this.getViennaSigns();
        },
     
    },
    mounted() {
        
        this.getViennaSigns();
        console.log("ola");
        /* console.log(this.signs); */
    }
}
</script>
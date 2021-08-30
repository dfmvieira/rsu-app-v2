<template>
    <CCard>
        <CCardHeader>
            <slot name='header'>
                Publish Sign
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
            <template #published="{item, index}">
                <td class="py-2">
                <input type="checkbox" id="checkbox" v-model="item.published" @change="update(item.id)">
                </td>
            </template>
            <template #details="{item, index}">
                <CCollapse :show="details.includes(index)">
                    <CForm>
                        Published: 
                        <input type="checkbox" id="checkbox" v-model="item.published">
                        <CButton
                            @click="update(item.id)"
                            size="sm" 
                            color="primary"
                            >
                            Guardar
                        </CButton>
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
                {key: 'entityId', label: 'Entity Id'},
                {key: 'name', label: 'Name'},  
                {key: 'GUID', label: 'GUID'},
                {key: 'viennaSignId', label: 'Vienna Sign Id'},
                {key: 'locked', label: 'Locked'},
                {key: 'status', label: 'Status'},
                {key: 'comment', label: 'Comment'},
                {key: 'latitude', label: 'Latitude'},              
                {key: 'longitude', label: 'Longitude'}, 
                {key: 'published', label: 'Published'},  
                /* { 
                    key: 'show_details', 
                    label: 'Options', 
                    _style: 'width:1%', 
                    sorter: false, 
                    filter: false
                }    */        
            ],
            details: [],
            editModal: false,
            ivi: {
                id: '',
                published: '',
                _method:"patch"
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
            
        update(id){
            console.log(this.ivi)
         /*    let self = this; */
           axios.put(`api/ivisign/publicationupdate/${id.toString()}`, this.ivi)
               .then(response=>{
                console.log("sucess")
                
                self.message = 'Sign successfully published.';
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
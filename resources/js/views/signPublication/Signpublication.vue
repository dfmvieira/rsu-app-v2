<template>
    <CCard>
        <CCardHeader>
            <slot name='header'>
                Publish Sign
            </slot>
        </CCardHeader>
        <CCardBody>
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
                <input type="checkbox" id="checkbox" v-model="item.published" @change="update(item)">
                </td>
            </template>
            </CDataTable>
        </CCardBody>

        <CToaster :autohide="3000">
            <template v-for="toast in fixedToasts">
                <CToast
                :key="'toast' + toast"
                :show="true"
                header="Info"
                style="max-height: 90px;"
                >
                    {{ toastMessage }}
                </CToast>
            </template>
        </CToaster>
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
                {key: 'GUID', label: 'GUID'},
                {key: 'viennaSignId', label: 'Vienna Sign Id'},
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
            dismissSecs: 7,
            dismissCountDown: 0,
            showDismissibleAlert: false,

            // TOAST
            toastMessage: '',
            fixedToasts: 0

        }
    },
    methods: {
        getIVIS() {
            axios.get('api/ivisign?token=' + localStorage.getItem("api_token"))
            .then(response => {
                this.ivis = response.data
            }).catch(err => {
                console.log(err)
            })
        },
        
        toggleDetails (index) {
            const position = this.details.indexOf(index)
            position !== -1 ? this.details.splice(position, 1) : this.details.push(index)
        },
            
        update(sign){
           axios.put('api/ivisign/publicationupdate/' + sign.id, sign )
               .then(response => {
                    this.insertToast(response.data.message)
               })
               .catch(err => {
                  console.log(err)
               })
        },

        // Insert Toasts
        insertToast(message) {
            this.fixedToasts++
            this.toastMessage = message
        },


        countDownChanged (dismissCountDown) {
            this.dismissCountDown = dismissCountDown
        },
    
    },
    mounted() {
        this.getIVIS();
    }
}
</script>
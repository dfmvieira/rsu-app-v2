<template>
    <CForm>
        <CInput
            label='Name'
            :lazy='false'
            v-model="IviSignMap.name"
            placeholder="Name"
        />

        
        <CRow>
            <label style="padding-left: 19px">Vienna Sign</label>
        </CRow>
        
        <CRow >
            <CButton
                color="primary"
                size=""
                class="m-2"
                @click="viennaSignsModal = true"
                v-if="!IviSignMap.viennaSignID"
                style="margin:0px 0px 10px 20px"
            >
                Click here to choose a vienna sign
            </CButton>
            
            <div v-if="IviSignMap.viennaSignID" style="border: 1px solid; border-color: #d8dbe0; border-radius: 0.25rem; padding: 10px; margin:0px 0px 15px 15px" v-on:click="viennaSignsModal = true">
                <img v-bind:src="finalSelectedViennaSign.img" style="width:100px; margin-left: auto; margin-right: auto; display: block">
                <label style="display: inline-block">{{ finalSelectedViennaSign.id }} - {{ finalSelectedViennaSign.name }}</label>
            </div>
        </CRow>

        <CTextarea
            label="Comment"
            vertical
            rows="4"
            v-model="IviSignMap.comment"
        />

        <label>Status</label>
        <v-select 
            v-model="IviSignMap.status"
            :options="[{value:1, label: 'Active'}, {value: 0, label: 'Inactive'}]" 
            placeholder="Select option"
        />

        <CModal
            title="Choose a Vienna Sign"
            color="primary"
            :show.sync="viennaSignsModal"
            :centered=true
            size="xl"
        >
            <vue-select-image
                :dataImages="this.viennaSigns"
                :useLabel=true
                w="120px"
                h="120px"
                @onselectimage="onSelectedViennaSign"
                >
            </vue-select-image>

            <template #footer>
                <CButton @click="cancelviennaSignsModal();" color="danger">Discard</CButton>
                <CButton @click="updateViennaSign()" color="success">Accept</CButton>
            </template>
        </CModal>

    </CForm>
</template>

<script>
import axios from 'axios'
import Vue from 'vue'
import vSelect from 'vue-select'
import VueSelectImage from 'vue-select-image'
import 'vue-select/dist/vue-select.css'
import 'vue-select-image/dist/vue-select-image.css'
import * as VueGoogleMaps from 'vue2-google-maps'

Vue.use(VueGoogleMaps, {
  load: {
    key: 'AIzaSyC0XdUsH5A6srnoLVH2q9WA1lhiHcVSF3w'
  }
})

export default {
    name: 'InsertSign',
    components: {
        vSelect,
        VueSelectImage 
    },
    data() {
        return {
            name: 'Insert Sign',
            IviSignMap: {
                name: '',
                viennaSignID: null,
                coordinates: {
                    latitude: null,
                    longitude: null
                },
                comment: '',
                status: null,
            },
            viennaSigns: [],
            viennaSignsModal: false,
            selectedViennaSign: [],
            finalSelectedViennaSign: {
                id: '',
                name: '',
                img: ''
            },

            mapModal: false,
            map: {
                center: {}
            }
        }
        
    },
    methods: {
        getViennaSigns() {
            axios.get('api/viennasigns/').then(response => {
                console.log(response.data)
                response.data.forEach((item) => {
                    this.viennaSigns.push({
                        'id': item.id,
                        'src': item.image,
                        'alt': item.name
                    })
                })
            })
        },

        insertSign() {
            console.log('here');
            axios.post('api/ivisign/insertivisign', this.IviSignMap).then(response => {
                console.log(response);
            });
        },

        onSelectedViennaSign(item) {
            this.selectedViennaSign = item
        },

        cancelviennaSignsModal() {
            this.viennaSignsModal = false
        },

        updateViennaSign() {
            this.IviSignMap.viennaSignID = this.selectedViennaSign.id

            this.finalSelectedViennaSign.id = this.selectedViennaSign.id
            this.finalSelectedViennaSign.img = this.selectedViennaSign.src
            this.finalSelectedViennaSign.name = this.selectedViennaSign.alt

            this.viennaSignsModal = false
        },
    
    },
    created() {
        this.getViennaSigns();
    },

}
</script>>
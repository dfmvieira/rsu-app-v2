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
            <div v-show="IviSignMap.viennaSignID === null"> 
                <CButton
                    color="primary"
                    size=""
                    class="m-2"
                    @click="openAddEditModal"
                    style="margin:0px 0px 10px 20px; background-color: #424249"
                >
                    Click here to choose a vienna sign
                </CButton>
            </div>
            <div v-if="IviSignMap.viennaSignId" style="border: 1px solid; border-color: #d8dbe0; border-radius: 0.25rem; padding: 10px; margin:0px 0px 15px 15px" v-on:click="viennaSignsModal = true">
                <img v-bind:src="finalSelectedViennaSign.img" style="width:100px; margin-left: auto; margin-right: auto; display: block">
                <label style="display: inline-block">{{ finalSelectedViennaSign.id }} - {{ finalSelectedViennaSign.name }}</label>
            </div>
            <div v-else style="border: 1px solid; border-color: #d8dbe0; border-radius: 0.25rem; padding: 10px; margin:0px 0px 15px 15px" v-on:click="viennaSignsModal = true">
                <!-- <img v-bind:src="finalSelectedViennaSign.img" style="width:100px; margin-left: auto; margin-right: auto; display: block"> -->
                <label style="display: inline-block">Click here to choose a Vienna Sign</label>
            </div>
        </CRow>

        <CTextarea
            label="Comment"
            vertical
            rows="4"
            v-model="IviSignMap.comment"
        />

        <CRow>
            <label style="margin: 0px 0px 10px 20px"><b>Coordinates: </b>{{ IviSignMap.coordinates.lat }},{{ IviSignMap.coordinates.lng }}</label>
        </CRow>

        <CRow>
            <CCol lg="4">
                <CButton @click="addDetectionZone()" color="light">Detection Zone</CButton>
            </CCol>
            <CCol lg="4">
                <CButton @click="addAwarenessZone()" color="light">Awareness Zone</CButton>
            </CCol>
            <CCol lg="4">
                <CButton @click="addRelevanceZone()" color="light">Relevance Zone</CButton>
            </CCol>
        </CRow>


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
                comment: '',
                coordinates: {
                    lat: 0,
                    lng: 0
                }
            },
            viennaSigns: [],
            selectedViennaSign: [],
            finalSelectedViennaSign: {
                id: '',
                name: '',
                img: ''
            },

            mapModal: false,
            map: {
                center: {}
            },

            viennaSignsModal: false,

            detectionZoneMarkers: [],
            detectionZonePolyLine: '',
            awarenessZoneMarkers: [],
            awarenessZonePolyLine: '',
            relevanceZoneMarkers: [],
            relevanceZonePolyLine: '',

            isEditSign: false,
        }
        
    },
    methods: {
        getViennaSigns() {
            axios.get('api/vienna/').then(response => {
                response.data.forEach((item) => {
                    this.viennaSigns.push({
                        'id': item.id,
                        'src': item.image,
                        'alt': item.name
                    })
                })
            })
        },
        
        resetForm() {
            this.IviSignMap = {
                name: '',
                comment: '',
                coordinates: {
                    lat: 0,
                    lng: 0
                },
                detectionZone: []
            },

            this.finalSelectedViennaSign = {
                id: '',
                name: '',
                img: ''
            },

            this.selectedViennaSign = {},

            this.detectionZoneMarkers =  [],
            this.detectionZonePolyLine =  '',
            this.awarenessZoneMarkers =  [],
            this.awarenessZonePolyLine =  '',
            this.relevanceZoneMarkers =  [],
            this.relevanceZonePolyLine =  ''
        },

        insertSign() {
            axios.post('api/ivisign/insertivisign?token=' + localStorage.getItem("api_token"), this.IviSignMap)
            .then(response => {
                this.$parent.$parent.updateAfterInsertSign(response.data)
                this.resetForm()
            }).catch(err => {
                console.log(err);
            });
        },

        editSign() {
            axios.put('api/ivisign/' + this.IviSignMap.id + '?token=' + localStorage.getItem("api_token"), this.IviSignMap)
            .then(response => {
                this.isEditSign = false
                this.$parent.$parent.updateAfterInsertSign(response.data)
            }).catch(err => {
                console.log(err);
            });
        },

        onSelectedViennaSign(item) {
            this.selectedViennaSign = item
        },

        cancelviennaSignsModal() {
            this.viennaSignsModal = false
        },

        openAddEditModal() {
            viennaSignsModal = true
            editAddSign = true
        },


        getViennaSignImage() {
            var viennaSignId = this.IviSignMap.viennaSignId

            axios.get('api/vienna/' + viennaSignId + '?token=' + localStorage.getItem("api_token"))
            .then(response => {
                var image = {
                    'id': response.data[0].id,
                    'src': response.data[0].image,
                    'alt': response.data[0].name,
                }
                this.onSelectedViennaSign(image)
                this.updateViennaSign()
            }).catch({
                
            });
        },

        updateViennaSign() {
            this.IviSignMap.viennaSignId = this.selectedViennaSign.id

            this.finalSelectedViennaSign.id = this.selectedViennaSign.id
            this.finalSelectedViennaSign.img = this.selectedViennaSign.src
            this.finalSelectedViennaSign.name = this.selectedViennaSign.alt

            this.viennaSignsModal = false
        },

        updateLockStatus() {
            var atualLockedStatus = this.IviSignMap.locked

            if (atualLockedStatus) {
                this.IviSignMap.locked = false
            } else { 
                this.IviSignMap.locked = true
            }
            var data = {
                id: this.IviSignMap.id,
                locked: this.IviSignMap.locked
            }

            axios.put('api/ivisign/updatelockstatus/' + this.IviSignMap.id + '?token=' + localStorage.getItem("api_token"), data)
            .then(response => {
                
            }).catch(err => {
                console.log(err)
            });
        },

        addDetectionZone() {
            this.$parent.$parent.showform = false
            this.$parent.$parent.insertStayToast("Click on map to draw a line for Detection Zone")
            this.IviSignMap.detectionZone = this.$parent.$parent.drawPolyLineOnMap(1)
        },
        addAwarenessZone() {
            this.$parent.$parent.showform = false
            this.$parent.$parent.insertStayToast("Click on map to draw a line for Detection Zone")
            this.IviSignMap.awarenessZone = this.$parent.$parent.drawPolyLineOnMap(2)
        },
        addRelevanceZone() {
            this.$parent.$parent.showform = false
            //this.$parent.$parent.insertStayToast("Click on map to draw a line for Detection Zone")
            this.IviSignMap.relevanceZone = this.$parent.$parent.drawPolyLineOnMap(3)
        },
    
    },
    created() {
        this.getViennaSigns();
    },

}
</script>

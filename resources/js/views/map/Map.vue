<template>
    <CCard>
        <CCardBody style="padding: 0; z-index:0">
            <GmapMap
                class="map"
                ref="mapRef"
                :center="center"
                :zoom="14"
                :tilt="0"
                :options="{ disableDefaultUI: true, fullscreenControl: true, mapTypeControl: true, rotateControl: false }"
                map-type-id="satellite"
            >
            <GmapInfoWindow :options="infoOptions" :position="infoWindowPos" :opened="infoWinOpen" @closeclick="infoWinOpen=false">
                <sign-info ref="signInfoRef"></sign-info>
            </GmapInfoWindow>
            <GmapMarker
                :key="index"
                v-for="(m, index) in iviMapSigns"
                :position="m.coordinates"
                :clickable="true"
                :draggable="m.draggable"
                @click="markerHandler(m)"
            />
            </GmapMap>
        </CCardBody>


        <!-- ############ INSERT/EDIT SIGN MODAL ############## -->
        <CModal 
        :show.sync="showform"
        :closeOnBackdrop=false>
            <insert-sign ref="insertSignRef"></insert-sign>

            <template #header>
                <h5>{{ formModalTitle }}</h5>
            </template>

            <template #footer>
                <CButton @click="cancelSignForm();" color="danger">Discard</CButton>
                <CButton @click="insertIviSign();" color="success">Accept</CButton>
            </template>
        </CModal>
        <!-- ###################################### -->

        <!-- ############ DELETE MODAL ############## -->
        <CModal 
        :show.sync="showConfirmationModal"
        :closeOnBackdrop=false>
            Are you sure you want to delete this sign? This cannot be undone.
            <template #footer>
                <CButton @click="closeModal();" color="danger">Discard</CButton>
                <CButton @click="deleteSign(signToDelete);" color="success">Accept</CButton>
            </template>
        </CModal>
        <!-- ###################################### -->

        <div ref="draggableContainer" id="toolbox">
            <div class="toolboxHeader" @mousedown="dragMouseDown">
                Toolbox
            </div>
            <div class="toolboxBody">
                <div class="toolboxItem">
                    <button class="toolboxButton"
                    v-on:click="addSign"
                    v-c-tooltip="'Add a new sign'">
                        <CIcon 
                            name="cil-plus"
                            style="color: white"
                            size="lg"
                        />
                    </button>
                </div>

                <div class="toolboxItemRight">
                   <button class="toolboxButton"
                   v-on:click="toolboxHandler('delete')"
                   v-c-tooltip="'Delete a sign'">
                        <CIcon 
                            name="cil-trash"
                            style="color: white"
                            size="lg"
                        />
                    </button>
                </div>

                <div class="toolboxItem">
                    <button class="toolboxButton"
                    v-on:click="toolboxHandler('edit')"
                    v-c-tooltip="'Edit a sign'">
                        <CIcon 
                            name="cil-pencil"
                            style="color: white"
                            size="lg"
                        />
                    </button>
                </div>

                <div class="toolboxItemRight">
                    <button class="toolboxButton"
                    v-c-tooltip="'Duplicate a sign'">
                        <CIcon 
                            name="cil-clone"
                            style="color: white"
                            size="lg"
                        />
                    </button>
                </div>

                <div class="toolboxItem">
                    <button class="toolboxButton"
                    v-c-tooltip="'Select an element'">
                        <b-icon icon="cursor-fill" style="width: 20px; height: 20px; color: white"></b-icon>
                    </button>
                </div>

                <div class="toolboxItemRight">
                    <button class="toolboxButton"
                    v-c-tooltip="'Drag an element'">
                        <b-icon icon="cursor" style="width: 20px; height: 20px; color: white"></b-icon>
                    </button>
                </div>
            </div>
        </div>

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

        <CToaster position="top-center">
            <template v-for="toast in stayToasts">
                <CToast
                :key="'toast' + toast"
                :show="showStayToast"
                header="Info"
                color="info"
                style="max-height: 90px;"
                >
                    {{ stayToastMessage }}
                </CToast>
            </template>
        </CToaster>
    </CCard>
</template>

<script>
import * as VueGoogleMaps from 'vue2-google-maps'
import Vue from 'vue'
import { BootstrapVue, BootstrapVueIcons } from 'bootstrap-vue'
import axios from 'axios'

import InsertSign from './InsertSign.vue'
import SignInfo from './SignInfo.vue'

Vue.use(VueGoogleMaps, {
  load: {
    key: 'AIzaSyC0XdUsH5A6srnoLVH2q9WA1lhiHcVSF3w'
    // key: ''
    // To use the Google Maps JavaScript API, you must register your app project on the Google API Console and get a Google API key which you can add to your app
    // v: 'OPTIONAL VERSION NUMBER',
    // libraries: 'places', //// If you need to use place input
  }
});
Vue.use(BootstrapVue)
Vue.use(BootstrapVueIcons)


export default {
    name: 'Map',
    components: {
        "insert-sign": InsertSign,
        "sign-info": SignInfo
    },
    computed: {
    },
    data () {
        return {
            center:{lat: 39.7541724, lng: -8.8759984},
            
            currentMidx: null,
            toolbox: {
                positions: {
                    clientX: undefined,
                    clientY: undefined,
                    movementX: 0,
                    movementY: 0
                }
            },
            iviMapSigns: null,

            showform: false,

            // Toasts
            toastMessage: '',
            fixedToasts: 0,

            showStayToast: true,
            stayToastMessage: '',
            stayToasts: 0,

            // INFO WINDOW
            //signInfoModal: false,
            
            infoContent: '',
            infoLink: '',
            infoWinOpen: false,
            infoWindowPos: {
                'lat': 0,
                'lng': 0
            },
            infoOptions: {
                pixelOffset: {
                    width: 0,
                    height: -35
                }
            },


            tempImage: '',


            // ADD/EDIT MODAL
            editAddSign: false,
            formModalTitle: 'Insert a Sign',
            newSignMarker: '',

            // DELETE
            showConfirmationModal: false,
            signToDelete: [],

            // TOOLBOX
            addToggle: false,
            deleteToggle: false,
            editToggle: false,
            selectToggle: false,
            dragToggle: false,


        }
    },
    methods: {
        getIviMapSigns() {
            axios.get('api/ivisign?token=' + localStorage.getItem('api_token'))
            .then(response => {
                this.iviMapSigns = response.data
            }).catch(err => {
                console.log(err)
            });
        },

        toolboxHandler(action) {
            if (action == 'delete') { // if not edit or select
                this.deleteToggle = true
            } else if (action == 'edit') {
                this.editToggle = true
            }
        },

        markerHandler(item) {
            if (this.deleteToggle) {
                if (item.locked) {
                    this.insertToast("Can't delete a locked sign. Unlock it first to delete")
                    this.deleteToggle = false
                } else {
                    this.signToDelete = item
                    this.showConfirmationModal = true
                }
            } else if (this.editToggle) {
               if (item.locked) {
                    this.insertToast("Can't edit a locked sign. Unlock it first to edit")
                    this.editToggle = false
                } else {
                    this.editSign(item)
                }
            } else if (this.dragToggle) {
                if (item.locked) {
                    this.insertToast("Can't edit a locked sign. Unlock it first to edit")
                    this.dragToggle = false
                } else {
                    this.dragElement()
                }
            } else {
                this.toggleInfoWindow(item)
            }
        },

        signFormHandler() {
            if (!this.$refs.insertSignRef.isEditSign) {
                this.$refs.insertSignRef.insertSign()
            } else { 
                this.$refs.insertSignRef.editSign()
            }
        },

        insertIviSign() {
            this.$refs.insertSignRef.insertSign()
        },

        addSign() {   
            this.$refs.mapRef.$mapPromise.then((map) => {
                var listener = map.addListener("click", (mapsMouseEvent) => {
                    
                    // Add marker in click location
                    this.newSignMarker = new google.maps.Marker({
                        position: mapsMouseEvent.latLng,
                        map: map,
                        icon: 'http://maps.google.com/mapfiles/ms/icons/blue.png'
                    });

                    //map.removeListener(listenerHandler)
                    this.showform = true

                    var coordinates = mapsMouseEvent.latLng.toJSON();

                    this.$refs.insertSignRef.IviSignMap.coordinates.lat = coordinates.lat
                    this.$refs.insertSignRef.IviSignMap.coordinates.lng = coordinates.lng

                    listener.remove()
                });
                
            });
        },

        editSign(sign) {
            this.$refs.insertSignRef.IviSignMap = sign
            this.$refs.insertSignRef.getViennaSignImage()
            this.formModalTitle = "Edit Sign"
            this.showform = true
        },

        deleteSign(sign) {
            axios.delete('api/ivisign/' + sign.id + '?token=' + localStorage.getItem("api_token"))
            .then(response => {
                this.showConfirmationModal = false
                this.signToDelete = []
                this.infoWinOpen = false
                this.deleteToggle = false

                this.insertToast(response.data.message)
                this.getIviMapSigns()

            }).catch(err => {
                console.log(err)
            })
        },

        dragElement() {
            
        },

        // Updates in map after some action
        updateAfterInsertSign(sign) {
            this.insertToast(sign.message)

            // update signs on map
            this.getIviMapSigns()

            // reset insert sign form
            this.$refs.insertSignRef.resetForm()

            // Close modal
            this.showform = false

            if (this.$refs.insertSignRef.detectionZoneMarkers.lenght !== 0) {
                this.$refs.insertSignRef.detectionZoneMarkers.forEach((marker) => {
                    marker.setMap(null)
                })

                this.$refs.insertSignRef.detectionZonePolyLine.setMap(null)
            }
            if (this.$refs.insertSignRef.awarenessZoneMarkers.lenght !== 0) {
                this.$refs.insertSignRef.awarenessZoneMarkers.forEach((marker) => {
                    marker.setMap(null)
                })

                this.$refs.insertSignRef.awarenessZonePolyLine.setMap(null)
            }
            if (this.$refs.insertSignRef.relevanceZoneMarkers.lenght !== 0) {
                this.$refs.insertSignRef.relevanceZoneMarkers.forEach((marker) => {
                    marker.setMap(null)
                })

                this.$refs.insertSignRef.relevanceZonePolyLine.setMap(null)
            }

        },

        cancelSignForm() {
            // close modal
            this.showform = false

            // reset insert sign form
            this.$refs.insertSignRef.resetForm()

            this.newSignMarker.setMap(null)
            this.newSignMarker = ''
        },

        async toggleInfoWindow(item) {
            
            // passing sign data to info component
            await this.getViennaSignImage(item.viennaSignId) //get image data

            this.$refs.signInfoRef.iviSignInfo = item
            this.$refs.signInfoRef.iviSignInfo.viennaImage = this.tempImage

            this.infoWindowPos = item.coordinates
            this.infoContent = this.$refs.signInfoRef.$el.outerHTML
            this.infoWinOpen = true
        },
        async getViennaSignImage(viennaId) {

            await axios.get('api/vienna/' + viennaId + '?token=' + localStorage.getItem("api_token"))
            .then(response => {
                this.tempImage = {
                    id: response.data[0].id,
                    src: response.data[0].image,
                    alt: response.data[0].name,
                }
            }).catch(err => {
                console.log(err)
            });
        },

        // Insert Toasts
        insertToast(message) {
            this.fixedToasts++
            this.toastMessage = message
        },

        insertStayToast(message) {
            this.stayToasts++
            this.stayToastMessage = message
        },

        // Draw lines for Zones of sign
        drawPolyLineOnMap(zoneType) {
            var linePoint = []
            this.$refs.mapRef.$mapPromise.then((map) => {

                var markerIcon = ''
                var polyColor = ''

                switch(zoneType) {
                    case 1:
                        markerIcon = 'http://maps.google.com/mapfiles/ms/icons/orange.png'
                        polyColor = '#ff9900'
                        break;
                    case 2:
                        markerIcon = 'http://maps.google.com/mapfiles/ms/icons/pink.png'
                        polyColor = '#e661ac'
                        break;
                    case 3:
                        markerIcon = 'http://maps.google.com/mapfiles/ms/icons/purple.png'
                        polyColor = '#8e67fd'
                        break;
                }

                var poly = new google.maps.Polyline({
                    strokeColor: polyColor,
                    strokeOpacity: 1.0,
                    strokeWeight: 3,
                })
                
                poly.setMap(map)
                var clickCounter = 0

                var listener = map.addListener("click", (mapsMouseEvent) => {
                    
                    clickCounter++

                    const path = poly.getPath();
                    // Because path is an MVCArray, we can simply append a new coordinate
                    // and it will automatically appear.
                    path.push(mapsMouseEvent.latLng);
                    // Add a new marker at the new plotted point on the polyline.
                    var marker = new google.maps.Marker({
                        position: mapsMouseEvent.latLng,
                        title: "#" + path.getLength(),
                        map: map,
                        icon: markerIcon
                    });

                    switch(zoneType) {
                        case 1:
                            this.$refs.insertSignRef.detectionZoneMarkers.push(marker)
                            this.$refs.insertSignRef.detectionZonePolyLine = poly
                            break;
                        case 2:
                            this.$refs.insertSignRef.awarenessZoneMarkers.push(marker)
                            this.$refs.insertSignRef.awarenessZonePolyLine = poly
                            break;
                        case 3:
                            this.$refs.insertSignRef.relevanceZoneMarkers.push(marker)
                            this.$refs.insertSignRef.relevanceZonePolyLine = poly
                            break;
                    }


                    linePoint.push(mapsMouseEvent.latLng.toJSON())

                    if (clickCounter == 1) {
                        this.stayToastMessage = ('Click on map to insert the second point for the Point')
                    }

                    if (clickCounter == 2) {
                        this.showform = true
                        this.showStayToast = false
                        listener.remove()
                    }
                });
            });

            return linePoint
        },

        // ############# TOOLBOX METHODS ################
        dragMouseDown: function (event) {
            event.preventDefault();
            // get the mouse cursor position at startup:
            this.toolbox.positions.clientX = event.clientX
            this.toolbox.positions.clientY = event.clientY
            document.onmousemove = this.elementDrag
            document.onmouseup = this.closeDragElement
        },
        elementDrag: function (event) {
            event.preventDefault()
            this.toolbox.positions.movementX = this.toolbox.positions.clientX - event.clientX
            this.toolbox.positions.movementY = this.toolbox.positions.clientY - event.clientY
            this.toolbox.positions.clientX = event.clientX
            this.toolbox.positions.clientY = event.clientY
            // set the element's new position:
            this.$refs.draggableContainer.style.top = (this.$refs.draggableContainer.offsetTop - this.toolbox.positions.movementY) + 'px'
            this.$refs.draggableContainer.style.left = (this.$refs.draggableContainer.offsetLeft - this.toolbox.positions.movementX) + 'px'
        },
        closeDragElement () {
            document.onmouseup = null
            document.onmousemove = null
        },
    },
    mounted() {
        this.getIviMapSigns();
    },
}
</script>

<style scoped>
    .map {
        height: 75vh;
    }

    #toolbox {
        position: absolute;
        z-index: 999999999999;
        text-align: center;
        background-color: #2c2c34;
        border: 1px #000;
        width: 100px;
        padding-bottom: 5px;
        
    }

    .toolboxHeader {
        padding: 10px;
        cursor: move;
        z-index: 10;
        background-color: #23242d;
        border: 1px #000;
    }

    .toolboxBody {
        width: 100%;
        clear: both;
    }

    .toolboxButton {
        
        width: 100%;
        height: 100%;

        border: 1px solid #424249;
        border-radius: 1px;

        background-color: #424249;

        padding: 5px 5px 5px 5px;

        text-align: center;
    }

    .toolboxItem {
        width: 50%;
        float: left;
        padding: 5px 2.5px 0px 5px;
    }

    .toolboxItemRight {
        width: 50%;
        float: left;
        padding: 5px 5px 0px 2.5px;
    }
</style>
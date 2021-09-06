<template>
    <CCard>
        <CCardBody style="padding: 0; z-index:0">
            <input
                id='map_input'
                type='input'
                placeholder="Map Search"
                class='form-control'
                v-model="searchInput"
                v-bind:class="{ hide: !searchToggle }"
            />

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
                :icon="{url: m.image, scaledSize:{width: 30, height: 30, f: 'px', b: 'px'}}"
                :clickable="true"
                :draggable="dragToggle"
                @click="markerHandler(m)"
                @dragend="dragMarkerHandler(m, $event.latLng)"
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
        :closeOnBackdrop=false
        color="danger"
        title="Delete sign?!">
            By deleting this sign, you will delete all the jobs and results that are related to this sign.<br><br>
            <span class="font-weight-bold">Are you sure you want to delete this sign?</span>
            <template #footer>
                <CButton @click="showConfirmationModal = false;" color="primary-color">Cancel</CButton>
                <CButton @click="deleteSign(signToDelete);" color="danger">Delete</CButton>
            </template>
        </CModal>
        <!-- ###################################### -->

        <!-- ############ INSERT RSU MODAL ############## -->
        <CModal 
        :show.sync="showRSUform"
        :closeOnBackdrop=false>
            <insert-rsu ref="insertRSURef"></insert-rsu>

            <template #header>
                <h5>RSU</h5>
            </template>

            <template #footer>
                <CButton @click="cancelRSUForm();" color="danger">Discard</CButton>
                <CButton @click="insertRSU();" color="success">Accept</CButton>
            </template>
        </CModal>
        <!-- ###################################### -->

        <div ref="draggableContainer" id="toolbox">
            <div class="toolboxHeader" @mousedown="dragMouseDown">
                Toolbox
            </div>
            <div class="toolboxBody">
                <div class="toolboxItemFull">
                    <button class="toolboxButton"
                    v-on:click="toolboxHandler('search')"
                    v-c-tooltip="'Search in Map'"
                    v-bind:class="{toolboxButtonActive: searchToggle}">
                        <b-icon icon="search" style="width: 20px; height: 20px; color: white"></b-icon>
                    </button>
                </div>
                
                <div class="toolboxItem">
                    <button class="toolboxButton"
                    v-on:click="toolboxHandler('add')"
                    v-c-tooltip="'Add a new sign'"
                    v-bind:class="{toolboxButtonActive: addToggle}">
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
                   v-c-tooltip="'Delete a sign'"
                   v-bind:class="{toolboxButtonActive: deleteToggle}">
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
                    v-c-tooltip="'Edit a sign'"
                    v-bind:class="{toolboxButtonActive: editToggle}">
                        <CIcon 
                            name="cil-pencil"
                            style="color: white"
                            size="lg"
                        />
                    </button>
                </div>

                <!-- <div class="toolboxItemRight">
                    <button class="toolboxButton"
                    v-c-tooltip="'Duplicate a sign'"
                    v-bind:class="{toolboxButtonActive: deleteToggle}">
                        <CIcon 
                            name="cil-clone"
                            style="color: white"
                            size="lg"
                        />
                    </button>
                </div> -->
                <div class="toolboxItemRight">
                    <button class="toolboxButton"
                    v-c-tooltip="'Add RSU'"
                    v-on:click="toolboxHandler('rsu')"
                    v-bind:class="{toolboxButtonActive: rsuToggle}">
                        <b-icon icon="cpu" style="width: 20px; height: 20px; color: white"></b-icon>
                    </button>
                </div>

                <div class="toolboxItem">
                    <button class="toolboxButton"
                    v-c-tooltip="'Drag an element'"
                    v-on:click="toolboxHandler('drag')"
                    v-bind:class="{toolboxButtonActive: dragToggle}">
                        <b-icon icon="cursor" style="width: 20px; height: 20px; color: white"></b-icon>
                    </button>
                </div>
                
                <div class="toolboxItemRight">
                    <button class="toolboxButton"
                    v-c-tooltip="'Select an element'"
                    v-on:click="toolboxHandler('select')"
                    v-bind:class="{toolboxButtonActive: selectToggle}">
                        <b-icon icon="cursor-fill" style="width: 20px; height: 20px; color: white"></b-icon>
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
import InsertRSU from './InsertRSU.vue'

Vue.use(VueGoogleMaps, {
    load: {
        key: 'AIzaSyC0XdUsH5A6srnoLVH2q9WA1lhiHcVSF3w',
        // To use the Google Maps JavaScript API, you must register your app project on the Google API Console and get a Google API key which you can add to your app
        // v: 'OPTIONAL VERSION NUMBER',
        libraries: 'places', //// If you need to use place input

        //// If you want to automatically install all the components this property must be set to 'true':
        installComponents: true
    }
});
Vue.use(BootstrapVue)
Vue.use(BootstrapVueIcons)


export default {
    name: 'Map',
    components: {
        "insert-sign": InsertSign,
        "sign-info": SignInfo,
        "insert-rsu": InsertRSU,
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


            // ADD/EDIT SIGN MODAL
            addSignListener: '',
            editAddSign: false,
            formModalTitle: 'Insert a Sign',
            newSignMarker: '',
            showform: false,

            // ADD RSU
            showRSUform: false,

            // DELETE
            showConfirmationModal: false,
            signToDelete: [],

            // SEARCH
            searchInput: '',

            // TOOLBOX
            searchToggle: false,
            addToggle: false,
            deleteToggle: false,
            editToggle: false,
            selectToggle: false,
            dragToggle: false,
            rsuToggle: false,
        }
    },
    methods: {
        initMap() {
            this.getIviMapSigns()

            /* const toolbox = document.getElementById("toolbox")

            const map = document.getElementsByClassName("vue-map")[0].children
            console.log(map[0])
            console.log(map[0].find("gm-style"))
            console.log(document.getElementsByClassName("gm-style").item(1))
            //console.log(document.getElementsByClassName("map")[0])
            this.$refs.mapRef.$el.appendChild(toolbox) */

            //console.log(this.$refs.mapRef.$el)
            


            /* const map = document.getElementById("map")[0]
            console.log(map)
            map.appendChild('#toolbox') */


           /*  this.$refs.mapRef.$mapPromise.then((map) => {
                console.log(map)
                map.appendChild('#toolbox')
            }) */
        },

        getIviMapSigns() {
            axios.get('api/ivisign?token=' + localStorage.getItem('api_token'))
            .then(response => {
                this.iviMapSigns = response.data
            }).catch(err => {
                console.log(err)
            });
        },

        toolboxHandler(action) {
            if (action == 'search') {
                if (this.searchToggle) {
                    this.searchToggle = false
                } else {
                    this.searchToggle = true
                }
            }else if (action == 'add') {
                if (this.addToggle) {
                    this.addToggle = false
                    this.addSignListener.remove()
                } else {
                    this.addToggle = true,
                    this.deleteToggle = false,
                    this.editToggle = false,
                    this.selectToggle = false,
                    this.dragToggle = false,
                    this.rsuToggle = false
                    this.addSign()
                }
            } else if (action == 'delete') { // if not edit or select
                if (this.deleteToggle) {
                    this.deleteToggle = false
                } else {
                    this.addToggle = false,
                    this.deleteToggle = true,
                    this.editToggle = false,
                    this.selectToggle = false,
                    this.dragToggle = false,
                    this.rsuToggle = false
                }
            } else if (action == 'edit') {
                if (this.editToggle) {
                    this.editToggle = false
                } else {
                    this.addToggle = false,
                    this.deleteToggle = false,
                    this.editToggle = true,
                    this.selectToggle = false,
                    this.dragToggle = false,
                    this.rsuToggle = false
                }
            } else if (action == 'select') {
                if (this.selectToggle) {
                    this.selectToggle = false
                } else {
                    this.addToggle = false,
                    this.deleteToggle = false,
                    this.editToggle = false,
                    this.selectToggle = true,
                    this.dragToggle = false,
                    this.rsuToggle = false
                }
            } else if (action == 'drag') {
                if (this.dragToggle) {
                    this.dragToggle = false
                } else {
                    this.addToggle = false,
                    this.deleteToggle = false,
                    this.editToggle = false,
                    this.selectToggle = false,
                    this.dragToggle = true,
                    this.rsuToggle = false
                }
            } else if (action == 'rsu') {
                if (this.rsuToggle) {
                    this.rsuToggle = false
                } else {
                    this.addToggle = false,
                    this.deleteToggle = false,
                    this.editToggle = false,
                    this.selectToggle = false,
                    this.dragToggle = false,
                    this.rsuToggle = true
                }
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
            } else if (this.rsuToggle) {
                if (item.IDRSU == 0 || item.IDRSU === '') {
                    this.addRSU(item)
                } else {
                    this.insertToast("This sign has already an RSU!")
                }
            } else if (this.selectToggle) {
                this.selectSignMarker(item)
            } else {
                this.toggleInfoWindow(item)
            }
        },

        dragMarkerHandler(marker, location) {

            var coordinates = {
                latitude: location.lat(),
                longitude: location.lng()
            }
            
            if (this.dragToggle) {
                if (marker.locked) {
                    this.insertToast("Can't edit a locked sign. Unlock it first to edit")
                    this.dragToggle = false
                } else {
                    axios.put('api/ivisign/updatecoordinates/' + marker.id + '?token=' + localStorage.getItem("api_token"), coordinates)
                    .then(response => {
                        this.insertToast(response.data.message)
                    }).catch(err => {
                        console.log(err)
                    })
                }
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
                this.addSignListener = map.addListener("click", (mapsMouseEvent) => {
                    
                    // Add marker in click location
                    this.newSignMarker = new google.maps.Marker({
                        position: mapsMouseEvent.latLng,
                        map: map,
                        icon: 'http://maps.google.com/mapfiles/ms/icons/blue.png'
                    });

                    //map.removeListener(listenerHandler)
                    this.showform = true
                    this.addSignListener.remove()

                    var coordinates = mapsMouseEvent.latLng.toJSON();

                    this.$refs.insertSignRef.IviSignMap.coordinates.lat = coordinates.lat
                    this.$refs.insertSignRef.IviSignMap.coordinates.lng = coordinates.lng
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


        addRSU(sign) {   
            this.$refs.insertRSURef.rsu.signId = sign.id
            this.showRSUform = true
        },

        insertRSU() {
            this.$refs.insertRSURef.insertRSU();
        },

        selectSignMarker(marker) {
            axios.get('/api/ivisign/zones/' + marker.id + '?token=' + localStorage.getItem("api_token"))
            .then(response => {
                this.showPolyLines(response.data[0])
            }).catch(err => {
                console.log(err)
            })
        },

        showPolyLines(sign) {
            const detectionCoordinates = [
                { lat: sign.detection_latitude1, lng: sign.detection_longitude1 },
                { lat: sign.detection_latitude2, lng: sign.detection_longitude2 },
            ]
            const awarenessCoordinates = [
                { lat: sign.awareness_latitude1, lng: sign.awareness_longitude1 },
                { lat: sign.awareness_latitude2, lng: sign.awareness_longitude2 },
            ]
            const relevanceCoordinates = [
                { lat: sign.relevance_latitude1, lng: sign.relevance_longitude1 },
                { lat: sign.relevance_latitude2, lng: sign.relevance_longitude2 },
            ]


            this.$refs.mapRef.$mapPromise.then((map) => {
                var detectionPoly = new google.maps.Polyline({
                    strokeColor: '#ff9900',
                    strokeOpacity: 1.0,
                    strokeWeight: 3,
                })

                var awarenessPoly = new google.maps.Polyline({
                    strokeColor: '#e661ac',
                    strokeOpacity: 1.0,
                    strokeWeight: 3,
                })

                var relevancePoly = new google.maps.Polyline({
                    strokeColor: '#8e67fd',
                    strokeOpacity: 1.0,
                    strokeWeight: 3,
                })

                detectionPoly.setMap(map)
                awarenessPoly.setMap(map)
                relevancePoly.setMap(map)

                const detectionMarkers = []
                const awarenessMarkers = []
                const relevanceMarkers = []

                detectionCoordinates.forEach(detectionCoord => {
                    var detectionMarker = new google.maps.Marker({
                        position: detectionCoord,
                        map: map,
                        icon: 'http://maps.google.com/mapfiles/ms/icons/orange.png'
                    });
                    
                    detectionPoly.getPath().push(new google.maps.LatLng(detectionCoord))
                    detectionMarkers.push(detectionMarker)
                })

                awarenessCoordinates.forEach(awarenessCoord => {
                    var awarenessMarker = new google.maps.Marker({
                        position: awarenessCoord,
                        map: map,
                        icon: 'http://maps.google.com/mapfiles/ms/icons/pink.png'
                    });

                    awarenessPoly.getPath().push(new google.maps.LatLng(awarenessCoord))
                    awarenessMarkers.push(awarenessMarker)
                })

                relevanceCoordinates.forEach(relevanceCoord => {
                    var relevanceMarker = new google.maps.Marker({
                        position: relevanceCoord,
                        map: map,
                        icon: 'http://maps.google.com/mapfiles/ms/icons/purple.png'
                    });

                    relevancePoly.getPath().push(new google.maps.LatLng(relevanceCoord))
                    relevanceMarkers.push(relevanceMarker)
                })
                
            })
        },

        cancelSignForm() {
            // close modal
            this.showform = false
            this.addToggle = false
            //this.addSignListener.remove()

            if (typeof this.$refs.insertSignRef.detectionZoneMarkers.length !== 'undefined' && this.$refs.insertSignRef.detectionZoneMarkers.length !== 0) {
                this.$refs.insertSignRef.detectionZoneMarkers.forEach((marker) => {
                    marker.setMap(null)
                })
                console.log(this.$refs.insertSignRef.detectionZonePolyLine)
                this.$refs.insertSignRef.detectionZonePolyLine.setMap(null)
            }
            if (typeof this.$refs.insertSignRef.awarenessZoneMarkers.length !== 'undefined' && this.$refs.insertSignRef.awarenessZoneMarkers.length !== 0) {
                this.$refs.insertSignRef.awarenessZoneMarkers.forEach((marker) => {
                    marker.setMap(null)
                })

                this.$refs.insertSignRef.awarenessZonePolyLine.setMap(null)
            }
            if (typeof this.$refs.insertSignRef.relevanceZoneMarkers.length !== 'undefined' && this.$refs.insertSignRef.relevanceZoneMarkers.length !== 0) {
                this.$refs.insertSignRef.relevanceZoneMarkers.forEach((marker) => {
                    marker.setMap(null)
                })

                this.$refs.insertSignRef.relevanceZonePolyLine.setMap(null)
            }

            // reset insert sign form
            this.$refs.insertSignRef.resetForm()

            this.newSignMarker.setMap(null)
            this.newSignMarker = ''
        },

        cancelRSUForm() {
            this.showRSUform = false

            this.$refs.insertRSURef.resetForm()
        },

        async toggleInfoWindow(item) {
            
            // passing sign data to info component
            await this.getViennaSignImage(item.viennaSignId) //get image data

            if (item.IDRSU != 0) {
                var rsu = await this.getRSU(item.IDRSU)
                item.rsu = rsu
            }

            this.$refs.signInfoRef.iviSignInfo = item
            this.$refs.signInfoRef.iviSignInfo.viennaImage = this.tempImage

            this.infoWindowPos = item.coordinates
            this.infoContent = this.$refs.signInfoRef.$el.outerHTML
            this.infoWinOpen = true
        },

        async getRSU(IDRSU) {
            var rsu = ''
            await axios.get('api/rsu/' + IDRSU + '?token=' + localStorage.getItem("api_token"))
                .then(response => {
                    rsu = response.data[0]
                }).catch(err => {
                    console.log(err)
                })

                return rsu
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

        searchAutoComplete() {
            this.$refs.mapRef.$mapPromise.then((map) => {
                // Create the search box and link it to the UI element.
                
                const input = document.getElementById("map_input")
                const searchBox = new google.maps.places.SearchBox(input)

                map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

                // Bias the SearchBox results towards current map's viewport.
                map.addListener("bounds_changed", () => {
                    searchBox.setBounds(map.getBounds());
                });

                // Listen for the event fired when the user selects a prediction and retrieve more details for that place.
                searchBox.addListener("places_changed", () => {
                    const places = searchBox.getPlaces();

                    if (places.length == 0) {
                        return;
                    }

                    // For each place, get the icon, name and location.
                    const bounds = new google.maps.LatLngBounds();
                    places.forEach((place) => {
                    if (!place.geometry || !place.geometry.location) {
                        console.log("Returned place contains no geometry");
                        return;
                    }

                    if (place.geometry.viewport) {
                        // Only geocodes have viewport.
                        bounds.union(place.geometry.viewport);
                    } else {
                        bounds.extend(place.geometry.location);
                    }
                    });
                    map.fitBounds(bounds);
                });
            })
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

        // GET CURRENT USER LOCATION
        getCurrentLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition((position) => {
                    this.$refs.mapRef.$mapPromise.then((map) => {
                        const pos = {
                            lat: position.coords.latitude,
                            lng: position.coords.longitude,
                        }

                        map.setCenter(pos)
                    })
                })
            }
        }
    },
    mounted() {
        this.initMap()
        this.getCurrentLocation()
        //this.searchAutoComplete()
    },
}
</script>

<style scoped>
    .map {
        height: 75vh;
    }

    #toolbox {
        position: absolute;
        z-index: 2147483647;
        text-align: center;
        background-color: #2c2c34;
        border: 1px #000;
        width: 100px;
        padding-bottom: 5px;
        top: 60px;
        left: 10px;
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

    .toolboxButtonActive {
        background-color: #272729;
    }

    .toolboxItemFull {
        width: 100%;
        float: left;
        padding: 5px 5px 0px 5px;
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

    #map_input {
        position: absolute;
        z-index: 999999999999;
        background-color: #2c2c34;
        left: 180px;
        top: 12px;
        font-size: 15px;
        font-weight: 300;
        margin-left: 12px;
        padding: 0 11px 0 13px;
        text-overflow: ellipsis;
        width: 400px;
        max-width: 400px;
    }

    .hide {
        display: none;
    }
</style>

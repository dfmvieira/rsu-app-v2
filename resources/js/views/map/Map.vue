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
                <CLink :href="infoLink" target="_blank">{{infoContent}}</CLink>
            </GmapInfoWindow>
            <!-- <GmapMarker
                :key="index"
                v-for="(m, index) in markers"
                :position="m.position"
                :label="m.label"
                :title="m.title"
                :clickable="true"
                :draggable="m.draggable"
                @click="toggleInfoWindow(m, index)"
            /> -->
            </GmapMap>
        </CCardBody>

        <!-- <b-popover
            :show.sync="showform"
            placement="top">
            <insert-sign></insert-sign>
        </b-popover> -->

        <CModal 
        :show.sync="showform"
        :closeOnBackdrop=false>
            <insert-sign ref="insertSignRef"></insert-sign>

            <template #footer>
                <CButton @click="cancelCreateSign();" color="danger">Discard</CButton>
                <CButton @click="insertIviSign();" color="success">Accept</CButton>
            </template>
        </CModal>
        

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
    </CCard>
</template>

<script>
import * as VueGoogleMaps from 'vue2-google-maps'
import Vue from 'vue'
import { BootstrapVue, BootstrapVueIcons } from 'bootstrap-vue'
import axios from 'axios'

import InsertSign from './InsertSign.vue'

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
        "insert-sign": InsertSign
    },
    data () {
        return {
            center:{lat: 39.7541724, lng: -8.8759984},
            infoContent: '',
            infoLink: '',
            infoWindowPos: {
                lat: 0,
                lng: 0
            },
            infoOptions: {
                pixelOffset: {
                    width: 0,
                    height: -35
                }
            },
            infoWinOpen: false,
            currentMidx: null,
            toolbox: {
                positions: {
                    clientX: undefined,
                    clientY: undefined,
                    movementX: 0,
                    movementY: 0
                }
            },

            showform: false
            
        }
    },
    methods: {
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

        addSign() {
            console.log("teste")

            
            this.$refs.mapRef.$mapPromise.then((map) => {
                
                map.addListener("click", (mapsMouseEvent) => {
                    this.showform = true
                    console.log(mapsMouseEvent.latLng)

                    this.$refs.insertSignRef.IviSignMap.coordinates.latitude = mapsMouseEvent.Tb.x
                    this.$refs.insertSignRef.IviSignMap.coordinates.longitude = mapsMouseEvent.Tb.y
                });
            });
        },

        insertIviSign() {
            this.$refs.insertSignRef.insertSign();
        }
    }
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
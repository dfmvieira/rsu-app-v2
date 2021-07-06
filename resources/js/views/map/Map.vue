<template>
    <CCard>
        <CCardHeader>
            <CIcon name="cil-map"/>
            Map
        </CCardHeader>
        <CCardBody>
            <GmapMap
                :center="center"
                :zoom="14"
                :tilt="0"
                :options="{ disableDefaultUI: true, fullscreenControl: true, mapTypeControl: true, rotateControl: true }"
                map-type-id="satellite"
                style="height: 650px"
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

        <div ref="draggableContainer" id="toolbox">
            <div id="toolboxHeader" @mousedown="dragMouseDown">
                Toolbox
            </div>
        </div>
    </CCard>
</template>

<script>
import * as VueGoogleMaps from 'vue2-google-maps'
import Vue from 'vue'
import axios from 'axios'

Vue.use(VueGoogleMaps, {
  load: {
    key: 'AIzaSyC0XdUsH5A6srnoLVH2q9WA1lhiHcVSF3w'
    // key: ''
    // To use the Google Maps JavaScript API, you must register your app project on the Google API Console and get a Google API key which you can add to your app
    // v: 'OPTIONAL VERSION NUMBER',
    // libraries: 'places', //// If you need to use place input
  }
});


export default {
    name: 'Map',
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
            }
            
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
        }
    }
}
</script>

<style scoped>
    #toolbox {
        position: absolute;
        z-index: 9;
        text-align: center;
        background-color: #20202a;
        border: 1px #000;
    }

    #toolboxHeader {
        padding: 10px;
        cursor: move;
        z-index: 10;
        background-color: #23242d;
        border: 1px #000;
    }
</style>
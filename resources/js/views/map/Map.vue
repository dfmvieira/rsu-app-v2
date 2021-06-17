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
            <GmapMarker
                :key="index"
                v-for="(m, index) in markers"
                :position="m.position"
                :label="m.label"
                :title="m.title"
                :clickable="true"
                :draggable="m.draggable"
                @click="toggleInfoWindow(m, index)"
            />
            </GmapMap>
        </CCardBody>
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
})


export default {
    name: 'Map',
    data: () => {
        return {
            center:{lat: 39.7541724, lng: -8.8759984},
        }
    },
    methods: {
        
    }
}
</script>

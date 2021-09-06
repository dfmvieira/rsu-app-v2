<template>
    <CContainer v-bind:class="{ rsuHeight : iviSignInfo.rsu }" style="height: 220px; margin-top: 10px">
        <CRow>
            <CCol lg="8">
                <h3>Sign</h3>
                <label class='info'><b>Name: </b> {{ iviSignInfo.name }}</label><br/>
                <label class='info'><b>Latitude: </b> {{ iviSignInfo.coordinates.lat }}</label><br/>
                <label class='info'><b>Longitude: </b> {{ iviSignInfo.coordinates.lng }}</label><br/>
                <label class='info'><b>GUID: </b> {{ iviSignInfo.GUID }}</label><br/>
                <label class='info'><b>Comment: </b> {{ iviSignInfo.comment }}</label><br/>
            </CCol>

            <CCol lg="4">
                <div v-if="iviSignInfo.viennaSignId" style="border: 1px solid; border-color: #d8dbe0; border-radius: 0.25rem; padding: 10px;">
                    <img v-bind:src="iviSignInfo.viennaImage.src" style="width:100px; margin-left: auto; margin-right: auto; display: block">
                    <label class="info" style="text-align:center; display: inline-block">{{ iviSignInfo.viennaImage.id }} - {{ iviSignInfo.viennaImage.alt }}</label>
                </div>
            </CCol>
        </CRow>

        <CRow v-if="iviSignInfo.rsu" style="margin-top: 20px">
            <CCol lg="8">
                <h3>RSU</h3>
                <label class='info'><b>Name: </b> {{ iviSignInfo.rsu.name }}</label><br/>
                <label class='info'><b>Serial Number: </b> {{ iviSignInfo.rsu.serialNumber }}</label><br/>
                <label class='info'><b>Range: </b> {{ iviSignInfo.rsu.range }}</label><br/>
                <label class='info'><b>Hardware Details: </b> {{ iviSignInfo.rsu.hardwareDetails }}</label><br/>
            </CCol>

            <CCol lg="4">
            </CCol>
        </CRow>

        <CRow style="margin: 20px 0px 5px 0px" alignVertical="center">
            <CCol lg="6" style="vertical-align: middle;">
                <div v-on:click="updateLockStatus()">
                    <CIcon 
                        v-if="iviSignInfo.locked"
                        name="cil-lock-locked"
                        style="color: black"
                        size="lg"
                    />

                    <CIcon 
                        v-if="!iviSignInfo.locked"
                        name="cil-lock-unlocked"
                        style="color: black"
                        size="lg"
                    />
                </div>
            </CCol>
            <CCol lg="6" style="text-align: right; padding-right: 0px">
                <CButton @click="deleteSign();" color="danger" v-if="!iviSignInfo.locked">Delete Sign</CButton>
            </CCol>
        </CRow>        
    </CContainer>

    
</template>


<script>

import axios from 'axios'

export default {
    name: 'SignInfo',
    data () {
        return {
            iviSignInfo: {
                name: '',
                GUID: '',
                comment: '',
                coordinates: {
                    lat: '',
                    lng: ''
                },
            },
            showConfirmationModal: false,

            
        }
    },
    methods: {
        updateLockStatus() {
            var atualLockedStatus = this.iviSignInfo.locked

            if (atualLockedStatus) {
                this.iviSignInfo.locked = false
            } else { 
                this.iviSignInfo.locked = true
            }
            var data = {
                id: this.iviSignInfo.id,
                locked: this.iviSignInfo.locked
            }

            axios.put('api/ivisign/updatelockstatus/' + this.iviSignInfo.id + '?token=' + localStorage.getItem("api_token"), data)
            .then(response => {
                this.$parent.$parent.$parent.insertToast(response.data.message)
            }). catch(err => {
                console.log(err)
            });
        },
        deleteSign() {
            this.$parent.$parent.$parent.deleteToggle = true
            this.$parent.$parent.$parent.markerHandler(this.iviSignInfo)
        }

    },
    mounted() {
    }
}
</script>

<style scoped>
    .info {
        color: black;
    }

    .rsuHeight {
        height: 380px !important;
    }
</style>
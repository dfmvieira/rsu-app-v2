<template>
    <CForm>
        <CInput
            label='Name'
            v-model="rsu.name"
            placeholder="Name"
        />

        <CInput
            label='Serial Number'
            v-model="rsu.serialNumber"
            placeholder="Serial Number"
        />

        <CInput
            label='Range (in meters)'
            v-model="rsu.range"
            placeholder="Range (in meters)"
            type="number"
        />

        <CTextarea
            label="Hardware Details"
            vertical
            rows="4"
            v-model="rsu.hardwareDetails"
        />
    </CForm>
</template>

<script>
import axios from 'axios'

export default {
    name: "InsertRSU",
    data() {
        return {
            rsu: {
                signId: '',
                name: '',
                range: '',
                serialNumber: '',
                hardwareDetails: ''
            },
        }
    },

    methods: {
        insertRSU() {
            axios.post('api/rsu/insertrsu?token=' + localStorage.getItem("api_token"), this.rsu)
            .then(response => {
                console.log(response)
                this.$parent.$parent.updateAfterInsertSign(response.data.message)
                this.resetForm()
            }).catch(err => {
                console.log(err);
            });
        },

        resetForm() {
            this.rsu = {
                signId: '',
                name: '',
                range: '',
                serialNumber: '',
                hardwareDetails: ''
            }
        },
    }
    

}
</script>

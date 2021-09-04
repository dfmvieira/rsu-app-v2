<template>
    
</template>

<script>

import axios from 'axios'

export default {
    name: "SignsToMake",
    data() {
        return {
            
        signs: []
        }
    },
    methods: {
        getSignsToMake() {
            axios.get('/api/iviSign/signsToFactoryMake?token' + localStorage.getItem("api_token"))
            .then(response => {

            }).catch(err => {
                
            })
        }
    }
}
</script>

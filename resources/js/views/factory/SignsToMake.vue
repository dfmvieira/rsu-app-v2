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
            axios.get('/api/factory')
        }
    }
}
</script>

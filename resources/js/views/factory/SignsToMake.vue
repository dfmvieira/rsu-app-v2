<template>
    <CCard>
        <CCardHeader>
            <slot name='header'>
               Factory Signs
            </slot>
        </CCardHeader>
        <CCardBody>
            <CDataTable
                :items="signs"
                :fields="fields"
                column-filter
                table-filter
                items-per-page-select
                :items-per-page="5"
                hover
                sorter
                pagination
            >
                <template #madeByFactory="{item}">
                    <td>
                        <CBadge color="success" v-if="item.madeByFactory == 'Yes'" style="font-size: 16px">{{ item.madeByFactory }}</CBadge>
                        <CBadge color="danger" v-else style="font-size: 16px">{{ item.madeByFactory }}</CBadge>
                    </td>
                </template>

                <template #show_details="{item, index}">
                    <td class="py-2">
                    <CButton
                        color="primary"
                        variant="outline"
                        square
                        size="sm"
                        @click="toggleDetails(index)"
                    >
                        {{ FactorySignsDetails.includes(index) ? 'Hide' : 'Show' }}
                    </CButton>
                    </td>
                </template>

                <template #details="{item, index}">
                    <CCollapse :show="FactorySignsDetails.includes(index)">
                        <CCardBody style="background: #393a42">
                            <!-- <CMedia :aside-image-props="{src:item.image, height: 100 ,width: 100}">
                            <CButton color="success" @click="setMade(item, index)" style="margin-left: 15px">Mark as Made by Factory!</CButton> -->
                            <CRow>
                                <CCol lg="4">
                                   <CMedia :aside-image-props="{src:item.image, height: 120 ,width: 120}">
                                   </CMedia>
                                </CCol>
                                <CCol lg="4">
                                    <label><b>Coordinates: </b>{{item.latitude}},{{item.longitude}}</label>
                                </CCol>
                                <CCol lg="4">
                                    <CButton color="success" v-if="item.madeByFactory == 'No'" @click="setMade(item, index)" style="margin-left: 15px">Mark as Made!</CButton>
                                    <CButton color="danger" v-if="item.madeByFactory == 'Yes'" @click="setNotMade(item, index)" style="margin-left: 15px">Mark as Not Made!</CButton>
                                </CCol>
                            </CRow>

                            <!-- <CRow style="margin-top: 20px; text-align: right" v-if="item.madeByFactory == 0">
                                
                            </CRow> -->

                            <!-- <CForm>
                                <CRow>
                                    
                                </CRow>
                                <CRow>
                                    <v-date-picker
                                        mode="range"
                                        v-model="deployInfo.date"
                                        is-inline
                                    />
                                </CRow>
                                <CRow style="margin-top: 20px; text-align: right">
                                    <CButton color="primary" @click="deployGroup">Submit</CButton>
                                </CRow>
                            </CForm> -->
                            
                            
                            
                        </CCardBody>
                    </CCollapse>
                </template>
            </CDataTable>
        </CCardBody>

        <CToaster :autohide="3000">
            <template v-for="toast in fixedToasts">
                <CToast
                :key="'toast' + toast"
                :show="true"
                header="Info"
                style="max-height: 100px;"
                >
                    {{ toastMessage }}
                </CToast>
            </template>
        </CToaster>
    </CCard>

</template>

<script>
    import axios from 'axios'

    export default {
        name: "FactorySigns",
        data() {
            return {
                signs: [],
                fields: [
                    {key: 'id', label: 'ID'},
                    {key: 'entityId', label: 'Entity ID'},
                    {key: 'GUID', label: 'GUID'},
                    {key: 'viennaSignId', label: 'Vienn Sign ID'},
                    {key: 'madeByFactory', label: 'Made by Factory'},
                    {
                        key: 'show_details',
                        label: 'Show',
                        _style: 'width: 1%',
                        sorter: false,
                        filter: false
                    }
                ],
                FactorySignsDetails: [],

                /* deployInfo: {
                    id: '',
                    observation: '',
                    date: ''
                }, */

                // TOAST
                toastMessage: '',
                fixedToasts: 0
            }
        },
    methods: {
        getSignsToMake() {
            axios.get('api/ivisign/factorysigns?token=' + localStorage.getItem("api_token"))
            .then(response => {
                this.signs = response.data
            }).catch(err =>{
                console.log(err)
            })
        },
        toggleDetails(index) {
                    const position = this.FactorySignsDetails.indexOf(index)
                    position !== -1 ? this.FactorySignsDetails.splice(position, 1) : this.FactorySignsDetails.push(index)
                },

        setMade(sign, index) {
            axios.put('api/ivisign/setmade/' + sign.id + '?token=' + localStorage.getItem("api_token"), sign)
            .then(response => {
                this.signs[index].madeByFactory = 'Yes'
                this.insertToast(response.data.message)
            }).catch(err => {
                console.log(err)
            })
        },

        setNotMade(sign, index) {
            axios.put('api/ivisign/setmade/' + sign.id + '?token=' + localStorage.getItem("api_token"), sign)
            .then(response => {
                this.signs[index].madeByFactory = 'No'
                this.insertToast(response.data.message)
            }).catch(err => {
                console.log(err)
            })
        },

        // Insert Toasts
        insertToast(message) {
            this.fixedToasts++
            this.toastMessage = message
        },
    },
    mounted() {
        this.getSignsToMake()
    }
    }
</script>

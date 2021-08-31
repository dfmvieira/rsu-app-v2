<template>
    <CCard>
        <CCardHeader>
            <slot name='header'>
                Deploy Groups
            </slot>
        </CCardHeader>
        <CCardBody>
            <CDataTable
                :items="deployGroups"
                :fields="fields"
                column-filter
                table-filter
                items-per-page-select
                :items-per-page="5"
                hover
                sorter
                pagination
            >
                <template #deployed="{item}">
                    <td>
                        <CBadge color="success" v-if="item.deployed == 'Yes'" style="font-size: 16px">{{ item.deployed }}</CBadge>
                        <CBadge color="danger" v-else style="font-size: 16px">{{ item.deployed }}</CBadge>
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
                        {{ deployGroupDetails.includes(index) ? 'Hide' : 'Show' }}
                    </CButton>
                    </td>
                </template>

                <template #details="{item, index}">
                    <CCollapse :show="deployGroupDetails.includes(index)">
                        <CCardBody style="background: #393a42">
                            <CRow>
                                <CCol lg="6">
                                    <CListGroupItem style="text-align: center; background: #20202a">
                                            <span><b>SIGNS</b></span>
                                        </CListGroupItem>
                                    <CListGroup>
                                        <CListGroupItem
                                            v-for="(item, index) in item.signs"
                                            v-bind:key="item.id"
                                            style="background: #23232a">
                                            {{ item.name }} - {{ item.GUID }}
                                        </CListGroupItem>
                                    </CListGroup>
                                </CCol>
                                <CCol lg="6">
                                    <label>Notes:</label>
                                    <CTextarea v-model="item.notes" readonly='true'></CTextarea>
                                </CCol>
                            </CRow>

                            <CRow style="margin-top: 20px; text-align: right" v-if="item.deployed == 'No'">
                                <CButton color="success" @click="setDeployed(item, index)" style="margin-left: 15px">Mark this group has deployed!</CButton>
                            </CRow>
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
        name: "DeployGroups",
        data() {
            return {
                deployGroups: [],
                fields: [
                    {key: 'id', label: 'ID'},
                    {key: 'name', label: 'Name'},
                    {key: 'notes', label: 'Notes'},
                    {key: 'deployed', label: 'Deployed'},
                    {
                        key: 'show_details',
                        label: 'Show',
                        _style: 'width: 1%',
                        sorter: false,
                        filter: false
                    }
                ],
                deployGroupDetails: [],

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
            getDeployGroups() {
                axios.get('/api/deploy/user?token=' + localStorage.getItem("api_token"))
                .then(response => {
                    this.deployGroups = response.data
                    console.log(this.deployGroups)
                }).catch(err =>{
                    this.insertToast(err)
                    console.log(err)
                })
            },

            toggleDetails(index) {
                const position = this.deployGroupDetails.indexOf(index)
                position !== -1 ? this.deployGroupDetails.splice(position, 1) : this.deployGroupDetails.push(index)
            },

            setDeployed(group, index) {
                axios.put('api/deploy/setdeployed/' + group.id + '?token=' + localStorage.getItem("api_token"), group)
                .then(response => {
                    this.deployGroups[index].deployed = 'Yes'
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
            this.getDeployGroups()
        }
    }
</script>

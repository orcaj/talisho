<template>
    <v-expansion-panel class="my-2">
        <messaging-header :user="$page.auth.user" :date="new Date().toISOString()" highlight></messaging-header>

        <messaging-body-wrapper>
            <v-form v-model="valid" ref="form">

                <v-row class="pt-4 pb-0">
                    <v-col cols="5" sm="4" md="2" align-self="center">
                        <span class="font-weight-bold subtitle-1">To:</span>
                    </v-col>
                    <v-col cols="7" sm="5" md="4">
                        <v-select
                            v-model="selectedGuests"
                            :items="discipline.team"
                            item-text="name"
                            item-value="id"
                            outlined
                            dense
                            :rules="[
                                (val) => val.length > 0 || 'Must select at least one guest'
                            ]"
                            :menu-props="{ maxHeight: '400' }"
                            label="Add Recipients"
                            multiple
                        ></v-select>
                    </v-col>
                </v-row>

                <v-row>
                    <v-col cols="5" sm="4" md="2" class="pt-0">
                        <span class="font-weight-bold subtitle-1">Directive No.:</span>
                    </v-col>
                    <v-col cols="7" sm="5" md="4" class="pt-0">
                        <div>CD-{{ discipline.discipline.abbreviation }}-XXX</div>
                    </v-col>
                </v-row>

                <v-row>
                    <v-col cols="5" sm="4" md="2">
                        <span class="font-weight-bold subtitle-1">Discipline:</span>
                    </v-col>
                    <v-col cols="7" sm="5" md="4">
                        <div> {{ discipline.discipline.label }} </div>
                    </v-col>
                </v-row>

                <v-divider></v-divider>

                <v-row>
                    <v-col cols="12" sm="5" class="py-0">
                        <div class="font-weight-bold subtitle-1 pb-3">Subject:</div>

                        <v-text-field
                            v-model="subject"
                            outlined
                            dense
                            placeholder="Subject..."
                            :rules="[
                                (val) => val.length < 255 || 'Subject must be less than 255 characters.',
                                (val) => !!val || 'Subject required.'
                            ]"
                        ></v-text-field>
                    </v-col>
                </v-row>

                <v-row>
                    <v-col cols="12" sm="5"  class="py-0">
                        <div class="font-weight-bold subtitle-1 pb-3">Drawing No.:</div>

                        <v-text-field
                            v-model="drawingNumber"
                            outlined
                            dense
                            placeholder="Drawing Number..."
                            :rules="[
                                (val) => val.length < 255 || 'Drawing Number must be less than 255 characters.',
                            ]"
                        ></v-text-field>
                    </v-col>
                </v-row>

                <v-row>
                    <v-col cols="12" sm="5"  class="py-0">
                        <div class="font-weight-bold subtitle-1 pb-3">Specification No.:</div>

                        <v-text-field
                            v-model="specificationNumber"
                            outlined
                            dense
                            placeholder="Specification Number..."
                            :rules="[
                                (val) => val.length < 255 || 'Drawing Number must be less than 255 characters.',
                            ]"
                        ></v-text-field>
                    </v-col>
                </v-row>

                <upload-files
                    v-model="files"
                    @remove="removeFile"
                />

                <comments
                    v-model="directiveText"
                    title="Directive"
                    required
                />

                <v-row>
                    <v-btn class="ml-4 mr-0 mb-2 mx-sm-4 mb-sm-0 white--text" @click="createDirective" color="blue darken-5" min-width="150px">
                        Submit
                    </v-btn>
                    <v-btn class="ml-4 mb-2 mx-sm-0 mb-sm-0" min-width="150px" outlined color="blue darken-5"
                           @click="$emit('cancel')">
                        Cancel
                    </v-btn>
                </v-row>


            </v-form>

        </messaging-body-wrapper>
    </v-expansion-panel>
</template>

<script>
    import MessagingHeader from "../Documentation/MessagingHeader";
    import MessagingBodyWrapper from "../Documentation/MessagingBodyWrapper";
    import UploadFiles from "../UploadFiles";
    import HandlesFileUploads from "../../mixins/HandlesFileUploads";
    import Comments from "../Comments";

    export default {
        components: {
            MessagingHeader,
            MessagingBodyWrapper,
            UploadFiles,
            Comments
        },

        mixins: [
            HandlesFileUploads
        ],

        props: {
            discipline: {
                type: Object,
                required: true
            }
        },

        data() {
            return {
                valid: false,
                selectedGuests: [],
                subject: '',
                drawingNumber: '',
                directiveText: '',
                specificationNumber: '',
            }
        },

        methods: {
            createDirective() {
                if (this.$refs.form.validate()) {
                    const formData = new FormData();
                    formData.append('subject', this.subject);
                    formData.append('drawing_number', this.drawingNumber);
                    formData.append('specification_number', this.specificationNumber);
                    formData.append('directive', this.directiveText);

                    this.selectedGuests.forEach((guestId, index) => {
                        formData.append('guests[' + index + ']', guestId);
                    });

                    this.appendFilesToFormData(formData);


                    this.$inertia.post(this.route('communication.construction-directive.store', [this.discipline.id]), formData, {
                        preserveState: true,
                        preserveScroll: true
                    });

                    this.$emit('close');
                }
            }
        }
    }
</script>

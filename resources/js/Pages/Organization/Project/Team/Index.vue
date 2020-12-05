<template>
    <v-row justify="center">
        <v-col cols="10">
            <v-expansion-panels v-model="opened" multiple>
                <team-card
                    v-for="discipline in disciplines"
                    :key="discipline.id"
                    :discipline="discipline"
                    :project-manager="projectManager"
                    :all-users-in-project="allTeamMembersAcrossProject"
                    :all-leads-in-project="allLeadsInProject"
                ></team-card>
            </v-expansion-panels>
        </v-col>
    </v-row>
</template>

<script>
    import Layout from "../../../../Shared/Layout";
    import ProjectShowHeader from "../../../../Shared/ProjectShowHeader";
    import TeamCard from "../../../../Shared/TeamCard";

    export default {
        layout: (h, component) => {
            const {organization, project} = component.data.props;
            return h(Layout, [
                h(ProjectShowHeader, {
                    props: {
                        organization,
                        project,
                        tab: 'team'
                    }
                }, [component]),
            ])
        },

        components: {
            TeamCard
        },

        data() {
            return {
               opened: []
            }
        },

        props: {
            organization: {
                type: Object,
                required: true
            },
            project: {
                type: Object,
                required: true
            },
            disciplines: {
                type: Array,
                required: true
            },
            projectManager: {
                type: Object,
                required: true
            }
        },

        computed: {
            allTeamMembersAcrossProject() {
                return this.disciplines.map((disc) => {
                    return disc.team.map((user) => user);
                }).flat();
            },
            allLeadsInProject() {
                return this.disciplines.map((disc) => disc.lead);
            }
        },

        created() {
            this.disciplines.forEach((discipline, index) => this.opened.push(index));
        }

    }
</script>


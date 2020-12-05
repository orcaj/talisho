export default {
    props: {
        quickList: {
            type: Array
        },
        idsOfCsisInProject: {
            type: Array
        },
        searchRoute: {
            type: String,
            required: true
        }
    },

    data() {
        return {
            selectedSearchCsis: [],
            selectedQuickListCsis: [],
            csiList: [],
            error: false,
            filter: null
        }
    },

    computed: {
        listForAutocomplete() {
            return this.csiList
                .filter(csi => !this.selectedSearchCsis.some(selectedCsi => selectedCsi.id === csi.id))
                .map(csi => ({...csi, disabled: this.quickListCsiIds.includes(csi.id)}));
        },
        quickListCsiIds() {
            return this.quickList.map(({id}) => id);
        },
    },

    methods: {
        updateSelectedQuickList(selected) {
            this.selectedQuickListCsis = selected;
        },

        removeSearchedCsi(removed) {
            this.selectedSearchCsis = this.selectedSearchCsis.filter(csi => csi.id !== removed.id);
        },

        updatedCsiSearchList(value) {
            if (value) {
                this.fetchCsis(value);
            } else {
                this.csiList = [];
            }
        },
        updateSelectedSearchCsi(csi) {
            this.selectedSearchCsis.push(csi);

            this.csiList = [];
        },
        async fetchCsis(searchParam) {
            try {
                const {data} = await window.axios.get(this.searchRoute, {
                    params: {
                        search: searchParam,
                        filterDivisionId: this.filter
                    }
                });

                this.csiList = data;

            } catch (error) {
                this.error = true;
            }
        },
    }
}

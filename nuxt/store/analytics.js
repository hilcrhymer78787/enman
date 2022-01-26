export const state = () => ({
    works: null,
})

export const mutations = {
    setWorks(state, works) {
        state.works = works
    },
}

export const actions = {
    async setWorks({ commit }, param) {
        await this.$axios
            .post(`/api/work/read/analytics`, param)
            .then((res) => {
                commit("setWorks", res.data);
            });
    },
}
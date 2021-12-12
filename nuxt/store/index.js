import moment from 'moment'
export const state = () => ({
    loginInfo: null,
    todayTasks: [],
    focusTasks: [],
    works: {
        daily: [],
        monthly: [],
    },
})

export const mutations = {
    setLoginInfo(state, loginInfo) {
        state.loginInfo = loginInfo
    },
    setTodayTasks(state, todayTasks) {
        todayTasks.forEach((task) => {
            let minute = task.works.reduce(function (sum, work) {
                return sum + work.work_minute;
            }, 0);
            task.minute = minute
        });
        state.todayTasks = todayTasks
    },
    setFocusTasks(state, focusTasks) {
        focusTasks.forEach((task) => {
            let minute = task.works.reduce(function (sum, work) {
                return sum + work.work_minute;
            }, 0);
            task.minute = minute
        });
        state.focusTasks = focusTasks
    },
    setWorks(state, works) {
        state.works = works
    },
}

export const actions = {
    setTokenRedirect({ }, token) {
        this.$cookies.set("token", token, {
            maxAge: 60 * 60 * 24 * 30,
        });
        $nuxt.$router.push("/");
    },
    setLoginInfoByToken({ commit, dispatch }) {
        this.$axios.get(`/api/user/bearer_authentication`)
            .then((res) => {
                // トークンが有効
                if (this.$cookies.get("token")) {
                    if (($nuxt.$route.name == 'login' || $nuxt.$route.name == 'login-newUser')) {
                        $nuxt.$router.push("/");
                    }
                    commit('setLoginInfo', res.data)
                }
            })
            .catch(() => {
                dispatch('logout')
            })
    },
    logout({ commit }) {
        this.$cookies.remove("token");
        if (!($nuxt.$route.name == 'login' || $nuxt.$route.name == 'login-newUser')) {
            $nuxt.$router.push("/login");
        }
        commit('setLoginInfo', false)
    },
    async setTodayTasks({ commit }) {
        const today = new Date();
        const year = today.getFullYear();
        const month = today.getMonth() + 1;
        const day = today.getDate();
        await this.$axios
            .get(
                `/api/task/read?year=${year}&month=${month}&day=${day}`
            )
            .then((res) => {
                commit('setTodayTasks', res.data)
            })
    },
    async setFocusTasks({ commit }) {
        const year = $nuxt.$route.query.year
        const month = $nuxt.$route.query.month
        const day = $nuxt.$route.query.day
        await this.$axios
            .get(
                `/api/task/read?year=${year}&month=${month}&day=${day}`
            )
            .then((res) => {
                commit('setFocusTasks', res.data)
            })
    },
    async setWorks({ commit }) {
        const year = $nuxt.$route.query.year
        const month = $nuxt.$route.query.month
        await this.$axios
        .get(`/api/work/read?year=${year}&month=${month}`)
        .then((res) => {
            commit("setWorks", res.data);
        });
    },
}
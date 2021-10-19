export const state = () => ({
    firstViewFlag: false,
    loginInfo: null,
    users: [],
    todayTasks: [],
    works: {
        daily: [],
        monthly: [],
    },
})

export const mutations = {
    setFirstViewFlag(state, firstViewFlag) {
        state.firstViewFlag = firstViewFlag
    },
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
    setWorks(state, works) {
        state.works = works
    },
}

export const actions = {
    async setLoginInfo({ commit }, form) {
        const email = form.email
        const password = form.password
        await this.$axios.get(`/api/user/read?email=${email}&password=${password}`)
            .then((res) => {
                this.$cookies.set("token", res.data.token, {
                    maxAge: 60 * 60 * 24 * 30,
                });
                commit('setLoginInfo', res.data)
            })
    },
    setLoginInfoByToken({ commit, dispatch }) {
        const token = this.$cookies.get("token")
        this.$axios.get(`/api/user/read?token=${token}`)
            .then((res) => {
                if (res.data.errorMessage) {
                    // トークンが有効ではない
                    this.$cookies.remove("token");
                    if (!($nuxt.$route.name == 'login' || $nuxt.$route.name == 'login-newUser')) {
                        $nuxt.$router.push("/login");
                    }
                    commit('setLoginInfo', false)
                } else {
                    // トークンが有効
                    if (this.$cookies.get("token")) {
                        if (($nuxt.$route.name == 'login' || $nuxt.$route.name == 'login-newUser')) {
                            $nuxt.$router.push("/");
                        }
                        commit('setLoginInfo', res.data)
                        dispatch('setTodayTasks')
                    }
                }
            })
            .catch(() => {
                this.$cookies.remove("token");
                if (!($nuxt.$route.name == 'login' || $nuxt.$route.name == 'login-newUser')) {
                    $nuxt.$router.push("/login");
                }
                commit('setLoginInfo', false)
            })
            .finally(() => {
                setTimeout(() => {
                    commit('setFirstViewFlag', true)
                }, 500);
            })
    },
    logout({ dispatch }) {
        this.$cookies.remove("token");
        dispatch('setLoginInfoByToken')
    },
    setTodayTasks({ state, commit }) {
        const today = new Date();
        const year = today.getFullYear();
        const month = today.getMonth() + 1;
        const day = today.getDate();
        this.$axios
            .get(
                `/api/task/read?year=${year}&month=${month}&day=${day}&token=${state.loginInfo.token}`
            )
            .then((res) => {
                commit('setTodayTasks', res.data)
            })
            .catch((err) => {
                alert("通信に失敗しました");
            })
    },
}
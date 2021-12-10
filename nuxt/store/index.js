export const state = () => ({
    loginInfo: null,
    todayTasks: [],
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
    setWorks(state, works) {
        state.works = works
    },
}

export const actions = {
    async setLoginInfo({ commit }, form) {
        const email = form.email
        const password = form.password
        await this.$axios.get(`/api/user/basic_authentication?email=${email}&password=${password}`)
            .then((res) => {
                this.$cookies.set("token", res.data.token, {
                    maxAge: 60 * 60 * 24 * 30,
                });
            })
    },
    setLoginInfoByToken({ commit, dispatch }) {
        this.$axios.get(`/api/user/bearer_authentication`)
            .then((res) => {
                if (res.data.errorMessage) {
                    // トークンが有効ではない
                    dispatch('logout')
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
    async setTodayTasks({ state, commit }) {
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
            .catch((err) => {
                alert("通信に失敗しました");
            })
    },
}
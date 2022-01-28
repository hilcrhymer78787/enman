import { GetterTree, ActionTree, MutationTree } from 'vuex'
import { apiWorkReadAnalyticsRequestType } from '@/types/api/work/read/analytics/request'
import { apiWorkReadAnalyticsResponseType } from '@/types/api/work/read/analytics/response'
import { AxiosRequestConfig, AxiosResponse, AxiosError } from "axios";
import moment from 'moment'
import axios from 'axios'
const CancelToken = axios.CancelToken;
let setCalendarsCancel: any = null;
let setLoginInfoByTokenCancel: any = null;
export type RootState = ReturnType<typeof state>

export const state = () => ({
    loginInfo: null,
    calendars: [],
})
export const mutations: MutationTree<RootState> = {
    setLoginInfo(state, loginInfo) {
        state.loginInfo = loginInfo
    },
    setCalendars(state, calendars) {
        state.calendars = calendars
    },
}

export const actions: ActionTree<RootState, RootState> = {
    setTokenRedirect({ }, token) {
        this.$cookies.set("token", token, {
            maxAge: 60 * 60 * 24 * 30,
        });
        this.$router.push("/");
    },
    setLoginInfoByToken({ commit, dispatch }) {
        if (setLoginInfoByTokenCancel) {
            setLoginInfoByTokenCancel()
        }
        this.$axios.get(`/api/user/bearer_authentication`, {
            cancelToken: new CancelToken(c => {
                setLoginInfoByTokenCancel = c
            }),
        })
            .then((res) => {
                // トークンが有効
                if (this.$cookies.get("token")) {
                    if ((this.$router.currentRoute.name == 'login' || this.$router.currentRoute.name == 'login-newUser')) {
                        this.$router.push("/");
                    }
                    commit('setLoginInfo', res.data)
                }
            })
            .catch((err) => {
                if (err.response) {
                    dispatch('logout')
                }
                if (err.response && err.response.status == 429) {
                    alert('一定時間にアクセスが集中したため、しばらくアクセスできません')
                }
            })
    },
    logout({ commit }) {
        this.$cookies.remove("token");
        if (!(this.$router.currentRoute.name == 'login' || this.$router.currentRoute.name == 'login-newUser')) {
            this.$router.push("/login");
        }
        commit('setLoginInfo', false)
    },
    async setCalendars({ commit }) {
        const year = this.$router.currentRoute.query.year
        const month = this.$router.currentRoute.query.month
        if (setCalendarsCancel) {
            setCalendarsCancel()
        }
        await this.$axios
            .get(`/api/work/read/calendar?year=${year}&month=${month}`, {
                cancelToken: new CancelToken(c => {
                    setCalendarsCancel = c
                }),
            })
            .then((res) => {
                commit("setCalendars", res.data.calendars);
            });
    },
}
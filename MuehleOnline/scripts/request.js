    let host =
        import.meta.env.SERVER_PROTOCOL +
        import.meta.env.SERVER_HOST_NAME + ":" +
        import.meta.env.SERVER_API_PORT + "/";


    export async function Request(url, data, method = "Post") {
        return await fetch(
                host + url, {
                    method: method,
                    mode: "cors",
                    headers: {
                        "Content-Type": "application/json",
                        Accept: "application/json",
                    },
                    body: JSON.stringify(data),
                })
            .then((response) => {
                if (response.ok) {
                    return response.json();

                } else {
                    return Promise.reject(response);
                }
            })

    }

    export async function AuthorizedRequest(url, data, method = "Post") {
        return await fetch(
                host + url, {
                    method: method,
                    mode: "cors",
                    headers: {
                        "Content-Type": "application/json",
                        Accept: "application/json",
                        Authorization: `Bearer ${localStorage.getItem("token")}`,

                    },
                    body: JSON.stringify(data),
                })
            .then((response) => {
                if (response.ok) {
                    return response.json();

                } else {
                    return Promise.reject(response);
                }
            })
    }

    export async function AuthorizedGetRequest(url) {
        return await fetch(
                host + url, {
                    method: "Get",
                    mode: "cors",
                    headers: {
                        "Content-Type": "application/json",
                        Accept: "application/json",
                        Authorization: `Bearer ${localStorage.getItem("token")}`,
                    },
                })
            .then((response) => {
                if (response.ok) {
                    return response.json();

                } else {
                    return Promise.reject(response);
                }
            })

    }
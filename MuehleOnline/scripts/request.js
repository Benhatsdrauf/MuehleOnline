    export async function Request(url, data, method = "Post") {
        return await fetch("http://localhost:5000/" + url, {
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
        return await fetch("http://localhost:5000/" + url, {
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
        return await fetch("http://localhost:5000/" + url, {
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
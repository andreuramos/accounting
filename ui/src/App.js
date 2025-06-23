import React, { useState } from "react";
import Login from "./pages/Login";
import Dashboard from "./pages/Dashboard";

function App() {
    const [token, setToken] = useState(() => localStorage.getItem("jwt") || "");

    function handleLogin(jwt) {
        localStorage.setItem("jwt", jwt);
        setToken(jwt);
    }
    function handleLogout() {
        localStorage.removeItem("jwt");
        setToken("");
    }

    if (!token) return <Login onLogin={handleLogin} />;
    return <Dashboard token={token} onLogout={handleLogout} />;
}

export default App;
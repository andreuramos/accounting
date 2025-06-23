import React from "react";
import { createRoot } from "react-dom/client";
import App from "./App";
import './index.css'; // <-- This is the crucial import

createRoot(document.getElementById("root")).render(<App />);
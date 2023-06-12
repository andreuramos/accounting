import React from 'react'
import ReactDOM from 'react-dom/client'
import {
  createBrowserRouter,
  RouterProvider,
} from "react-router-dom"
import { DashboardView } from './views/Dashboard.view'
import { LoginView } from './views/Login.view'

const router = createBrowserRouter([
  { path: "/", element: <DashboardView /> },
  { path: "/login", element: <LoginView /> },
])

ReactDOM.createRoot(document.getElementById('root') as HTMLElement).render(
  <React.StrictMode>
    <RouterProvider router={router} />
  </React.StrictMode>,
)

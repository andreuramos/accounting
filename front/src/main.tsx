import React from 'react'
import { create } from 'zustand'
import ReactDOM from 'react-dom/client'
import {
  RouterProvider,
  createBrowserRouter,
} from "react-router-dom"
import { routes } from './routes'

type AuthState = {
  jwtToken: string
  setJwtToken: (jwtToken: string) => void
}

export const authStore = create<AuthState>()((set) => ({
  jwtToken: null,
  setJwtToken: jwtToken => set(() => ({ jwtToken }))
}))


ReactDOM.createRoot(document.getElementById('root') as HTMLElement).render(
  <React.StrictMode>
    <RouterProvider router={createBrowserRouter(routes)} />
  </React.StrictMode>,
)

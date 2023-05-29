import { User } from '@domain/User'
import UserRepository from '@domain/UserRepository'

export default class InMemoryUserRepository implements UserRepository {
    private users: Array<User> = []

    public async add(user: User): Promise<Response> {
        this.users.push(user)
        return new Response()
    }

    public async findByEmail(email: string): Promise<User | null> {
        const user = this.users.find(user => user.email === email)
        return user ?? null
    }

    public async exists(email: string): Promise<boolean> {
        return this.users.some(user => user.email === email)
    }
}
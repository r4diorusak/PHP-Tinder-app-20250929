React Native skeleton notes

Recommended stack:
- Atomic design folder structure
- React Query for server state
- Recoil for local state (or Zustand)
- react-native-deck-swiper for Tinder-like swiping

Quick start (assumes you have React Native CLI environment):

1. `npx react-native init TinderApp` (or use Expo if preferred)
2. Install libs:
   - `npm install @tanstack/react-query recoil react-native-deck-swiper`
   - For navigation: `npm install @react-navigation/native` and follow native deps
3. Copy `frontend/src/` into your RN project and import the `CardStack` demo component.

I included a small `Card` component example to help you implement the swipe UI quickly.
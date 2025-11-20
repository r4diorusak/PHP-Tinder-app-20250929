# React Native Mobile App Setup Instructions

## Prerequisites

Before starting, make sure you have installed:

### Windows
1. **Node.js** (v18 or higher)
   - Download from: https://nodejs.org/
   - Verify installation: `node --version`

2. **React Native CLI**
   ```powershell
   npm install -g react-native-cli
   ```

3. **Android Studio** (for Android development)
   - Download from: https://developer.android.com/studio
   - Install Android SDK (API level 33 or higher)
   - Setup Android emulator (AVD)

4. **Java Development Kit (JDK 11)**
   - Download from: https://www.oracle.com/java/technologies/downloads/

### macOS (for iOS development)
1. **Xcode** (from App Store)
2. **CocoaPods**
   ```bash
   sudo gem install cocoapods
   ```

## Installation Steps

### 1. Navigate to Mobile Directory

```powershell
cd "d:\Documents\GitHub\CV Project\PHP-Tinder-Laravel\mobile"
```

### 2. Install Dependencies

```powershell
npm install
```

This will install:
- React Native
- React Navigation
- React Query (TanStack Query)
- Recoil
- Axios
- React Native Gesture Handler
- And other dependencies

### 3. Configure API Connection

Edit `src/services/api.ts` and update the API base URL:

**For Android Emulator:**
```typescript
const API_BASE_URL = 'http://10.0.2.2:8000/api';
```

**For iOS Simulator:**
```typescript
const API_BASE_URL = 'http://localhost:8000/api';
```

**For Physical Device:**
1. Find your computer's IP address:
   ```powershell
   ipconfig
   ```
   Look for "IPv4 Address" (e.g., 192.168.1.100)

2. Update API URL:
   ```typescript
   const API_BASE_URL = 'http://192.168.1.100:8000/api';
   ```

3. Make sure Laravel allows connections from your network:
   ```powershell
   php artisan serve --host=0.0.0.0 --port=8000
   ```

### 4. Install iOS Dependencies (macOS only)

```bash
cd ios
pod install
cd ..
```

## Running the App

### Android

1. **Start Metro bundler:**
   ```powershell
   npm start
   ```

2. **In a new terminal, run Android:**
   ```powershell
   npm run android
   ```

   Or manually:
   ```powershell
   npx react-native run-android
   ```

### iOS (macOS only)

1. **Start Metro bundler:**
   ```bash
   npm start
   ```

2. **In a new terminal, run iOS:**
   ```bash
   npm run ios
   ```

   Or manually:
   ```bash
   npx react-native run-ios
   ```

## Project Structure

```
mobile/
├── src/
│   ├── components/          # UI Components (Atomic Design)
│   │   ├── atoms/          # Basic building blocks
│   │   │   ├── Button.tsx
│   │   │   ├── CustomText.tsx
│   │   │   └── IconButton.tsx
│   │   ├── molecules/      # Combinations of atoms
│   │   │   ├── ActionButtons.tsx
│   │   │   ├── LikedPersonItem.tsx
│   │   │   └── PersonCard.tsx
│   │   └── organisms/      # Complex components
│   │       └── SwipeCards.tsx
│   ├── screens/            # App screens
│   │   ├── SplashScreen.tsx
│   │   ├── MainScreen.tsx
│   │   └── LikedListScreen.tsx
│   ├── services/           # API services
│   │   └── api.ts
│   ├── hooks/              # Custom React hooks
│   │   └── useApi.ts
│   ├── state/              # State management (Recoil)
│   │   └── atoms.ts
│   └── App.tsx             # Root component
├── android/                # Android native code
├── ios/                    # iOS native code
├── package.json
├── tsconfig.json
└── babel.config.js
```

## Architecture

### Atomic Design Pattern

The app follows Atomic Design principles:

1. **Atoms**: Smallest components (Button, Text, Icon)
2. **Molecules**: Combinations of atoms (PersonCard, ActionButtons)
3. **Organisms**: Complex features (SwipeCards)
4. **Screens**: Full pages (MainScreen, LikedListScreen)

### State Management

**Recoil Atoms:**
- `currentUserIdState`: Current user ID (default: 1)
- `currentCardIndexState`: Index of current card in stack
- `peopleStackState`: Array of people to swipe
- `matchNotificationState`: Match notification data

### Data Fetching (React Query)

Custom hooks in `useApi.ts`:
- `useRecommendedPeople()`: Fetch recommended people
- `useLikePerson()`: Mutate like action
- `useDislikePerson()`: Mutate dislike action
- `useLikedPeople()`: Fetch liked people list
- `useMatches()`: Fetch matches

## Features Implementation

### 1. Splash Screen
- Displays app logo for 2 seconds
- Automatically navigates to Main screen

### 2. Main Screen (Swipe Cards)
- Shows stack of person cards
- Swipe right → Like
- Swipe left → Dislike
- Manual buttons for like/dislike
- Auto-loads more cards when running low
- Shows match notification on mutual like

### 3. Liked List Screen
- Displays all liked people
- Shows profile picture, name, age, location, bio
- Pull to refresh functionality

## Troubleshooting

### Build Errors

**Android:**
```powershell
cd android
.\gradlew clean
cd ..
npm start -- --reset-cache
npm run android
```

**iOS:**
```bash
cd ios
pod deintegrate
pod install
cd ..
npm start -- --reset-cache
npm run ios
```

### Metro Bundler Issues

Clear cache and restart:
```powershell
npm start -- --reset-cache
```

### Cannot Connect to API

1. **Check Laravel server is running:**
   ```powershell
   php artisan serve
   ```

2. **For Android emulator, use** `10.0.2.2` instead of `localhost`

3. **For physical device:**
   - Computer and phone must be on same WiFi
   - Use computer's IP address
   - Laravel must serve on `0.0.0.0`:
     ```powershell
     php artisan serve --host=0.0.0.0
     ```

### CORS Issues

If you get CORS errors, update Laravel's `config/cors.php`:
```php
'paths' => ['api/*'],
'allowed_origins' => ['*'],
'allowed_methods' => ['*'],
'allowed_headers' => ['*'],
```

### Gesture Handler Issues

If swipe gestures don't work:
```powershell
npm install react-native-gesture-handler
npm install react-native-reanimated
```

Then rebuild the app.

## Development Tips

### Hot Reload
- Shake device or press `Ctrl+M` (Android) / `Cmd+D` (iOS)
- Enable "Hot Reloading" for instant updates

### Debug Menu
- **Android**: Shake device or `Ctrl+M`
- **iOS**: Shake device or `Cmd+D`
- **Emulator**: `Cmd+M` (Mac) or `Ctrl+M` (Windows)

### React Native Debugger
Install standalone debugger:
```powershell
npm install -g react-native-debugger
```

### View Logs
```powershell
# Android
npx react-native log-android

# iOS
npx react-native log-ios
```

## Performance Optimization

### Production Build

**Android:**
```powershell
cd android
.\gradlew assembleRelease
```
APK will be in: `android/app/build/outputs/apk/release/`

**iOS:**
Open Xcode → Product → Archive

### Optimize Images
- Use optimized image formats (WebP)
- Implement lazy loading for images
- Cache images locally

### Code Splitting
- Use React.lazy() for screens
- Implement pagination for large lists

## Testing

### Run Unit Tests
```powershell
npm test
```

### Run E2E Tests
Install Detox:
```powershell
npm install -g detox-cli
npm install --save-dev detox
```

## Additional Packages

### Useful Libraries to Add

**Image Picker:**
```powershell
npm install react-native-image-picker
```

**Camera:**
```powershell
npm install react-native-camera
```

**Push Notifications:**
```powershell
npm install @react-native-firebase/messaging
```

**Maps:**
```powershell
npm install react-native-maps
```

## Environment Variables

Create `.env` file in mobile directory:
```env
API_BASE_URL=http://10.0.2.2:8000/api
```

Install dotenv:
```powershell
npm install react-native-dotenv
```

## Publishing

### Android (Google Play)
1. Generate signing key
2. Configure `android/app/build.gradle`
3. Build release APK/AAB
4. Upload to Google Play Console

### iOS (App Store)
1. Configure signing in Xcode
2. Archive the app
3. Upload to App Store Connect via Xcode

---

Happy Coding! 📱✨

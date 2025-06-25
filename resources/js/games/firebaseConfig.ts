// Import the functions you need from the SDKs you need
import { initializeApp } from "firebase/app";
import { getDatabase } from "firebase/database";
import { getAnalytics } from "firebase/analytics";
// TODO: Add SDKs for Firebase products that you want to use
// https://firebase.google.com/docs/web/setup#available-libraries

// Your web app's Firebase configuration
// For Firebase JS SDK v7.20.0 and later, measurementId is optional
const firebaseConfig = {
  apiKey: "AIzaSyBdlwRF_MLNY5LFAU8LOyKyYKGGgHleX7o",
  authDomain: "teamapostas-c2268.firebaseapp.com",
  databaseURL: "https://teamapostas-c2268-default-rtdb.europe-west1.firebasedatabase.app",
  projectId: "teamapostas-c2268",
  storageBucket: "teamapostas-c2268.firebasestorage.app",
  messagingSenderId: "378599356819",
  appId: "1:378599356819:web:ff75008650f458f516d066",
  measurementId: "G-E4C0RMEKB3"
};

// Initialize Firebase
const app = initializeApp(firebaseConfig);
const analytics = getAnalytics(app);
export const db = getDatabase(app);